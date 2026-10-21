const conteneur = document.getElementById('liste-avis');

async function chargerAvis() {
  try {
    // 1. On envoie la requête au serveur, sans recharger la page
    const reponse = await fetch('../php/api-avis.php');
    if (!reponse.ok) {
      throw new Error('Erreur ' + reponse.status);
    }

    // 2. On transforme le JSON reçu en tableau Javascript
    const avis = await reponse.json();

    if (avis.length === 0) {
      conteneur.textContent = 'Aucun avis pour le moment.';
      return;
    }

    // 3. On crée une carte pour chaque avis
       avis.forEach(a => {
      const colonne = document.createElement('div');
      colonne.className = 'col-md-4 mb-3';

      const carte = document.createElement('div');
      carte.className = 'card p-3 h-100';

      const texte = document.createElement('p');
      texte.textContent = '« ' + a.description + ' »';

      const auteur = document.createElement('strong');
      auteur.textContent = '— ' + a.prenom + ' ' + '⭐'.repeat(a.note);

      carte.append(texte, auteur);
      colonne.appendChild(carte);
      conteneur.appendChild(colonne);
    });
  } catch (erreur) {
    conteneur.textContent = 'Les avis ne peuvent pas être chargés pour le moment.';
  }
}
chargerAvis();