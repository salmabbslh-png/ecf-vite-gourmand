<?php
if (extension_loaded('mongodb')) {
    echo "✅ L'extension MongoDB est bien chargée !";
} else {
    echo "❌ L'extension MongoDB n'est PAS chargée.";
}
