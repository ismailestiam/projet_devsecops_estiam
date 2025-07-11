<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Configuration CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Router simple
$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Suppression des paramètres de requête
$path = parse_url($request_uri, PHP_URL_PATH);

// Routes API
if (strpos($path, '/api/') === 0) {
    header('Content-Type: application/json');
    
    switch ($path) {
        case '/api/health':
            echo json_encode([
                'status' => 'OK',
                'service' => 'PHP Application',
                'timestamp' => date('c'),
                'php_version' => PHP_VERSION,
                'memory_usage' => memory_get_usage(true),
                'peak_memory' => memory_get_peak_usage(true)
            ]);
            break;
            
        case '/api/info':
            echo json_encode([
                'service' => 'PHP Application',
                'version' => '1.0.0',
                'php_version' => PHP_VERSION,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Built-in server',
                'document_root' => $_SERVER['DOCUMENT_ROOT'],
                'features' => [
                    'RESTful API',
                    'CORS enabled',
                    'Built-in routing',
                    'DevSecOps ready',
                    'Direct access (no proxy)'
                ],
                'security_note' => 'Cette application est directement accessible pour les tests de pénétration'
            ]);
            break;
            
        case '/api/products':
            if ($request_method === 'GET') {
                $products = [
                    ['id' => 1, 'name' => 'Ordinateur Portable', 'price' => 899.99, 'category' => 'Informatique'],
                    ['id' => 2, 'name' => 'Smartphone', 'price' => 599.99, 'category' => 'Téléphonie'],
                    ['id' => 3, 'name' => 'Tablette', 'price' => 399.99, 'category' => 'Informatique'],
                    ['id' => 4, 'name' => 'Casque Audio', 'price' => 149.99, 'category' => 'Audio'],
                    ['id' => 5, 'name' => 'Montre Connectée', 'price' => 299.99, 'category' => 'Wearable']
                ];
                
                echo json_encode([
                    'success' => true,
                    'count' => count($products),
                    'data' => $products
                ]);
            } elseif ($request_method === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                
                if (!isset($input['name']) || !isset($input['price'])) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'error' => 'Name and price are required'
                    ]);
                    break;
                }
                
                $newProduct = [
                    'id' => time(),
                    'name' => $input['name'],
                    'price' => floatval($input['price']),
                    'category' => $input['category'] ?? 'Général',
                    'created_at' => date('c')
                ];
                
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Product created successfully',
                    'data' => $newProduct
                ]);
            }
            break;
            
        case '/api/security-test':
            // Endpoint spécialement conçu pour les tests de pénétration
            echo json_encode([
                'message' => 'Endpoint de test de sécurité',
                'warning' => 'Cette application est exposée directement pour les tests de pénétration',
                'server_info' => [
                    'php_version' => PHP_VERSION,
                    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Built-in server',
                    'request_headers' => getallheaders(),
                    'server_vars' => array_filter($_SERVER, function($key) {
                        return strpos($key, 'HTTP_') === 0 || in_array($key, ['REQUEST_METHOD', 'REQUEST_URI', 'REMOTE_ADDR']);
                    }, ARRAY_FILTER_USE_KEY)
                ],
                'note' => 'Cet endpoint expose volontairement des informations pour faciliter les tests de sécurité'
            ]);
            break;
            
        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'API endpoint not found',
                'available_endpoints' => [
                    'GET /api/health',
                    'GET /api/info',
                    'GET /api/products',
                    'POST /api/products',
                    'GET /api/security-test'
                ]
            ]);
            break;
    }
} else {
    // Servir l'interface web
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application PHP - DevSecOps (Accès Direct)</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .container {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.18);
            max-width: 900px;
            margin: 20px;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .warning {
            background: rgba(255, 193, 7, 0.3);
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            font-weight: bold;
        }
        
        p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .api-section {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .api-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .api-button {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .api-button:hover {
            transform: translateY(-2px);
        }
        
        .security-button {
            background: linear-gradient(45deg, #f39c12, #e67e22);
        }
        
        .response-area {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            text-align: left;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
        }
        
        .product-form {
            display: grid;
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .form-input {
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .feature {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .feature h3 {
            margin-bottom: 0.5rem;
            color: #ffd700;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }
            
            p {
                font-size: 1rem;
            }
            
            .container {
                margin: 10px;
                padding: 1.5rem;
            }
            
            .api-buttons {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🐘 Application PHP</h1>
        
        <div class="warning">
            ⚠️ ACCÈS DIRECT - Cette application est accessible directement (sans reverse proxy) pour les tests de pénétration
        </div>
        
        <p>Application PHP native dans un environnement DevSecOps Docker, exposée directement pour les tests de sécurité en boîte blanche.</p>
        
        <div class="api-section">
            <h2>🔧 Test des APIs</h2>
            <div class="api-buttons">
                <button class="api-button" onclick="testAPI('/api/health')">Health Check</button>
                <button class="api-button" onclick="testAPI('/api/info')">Info Système</button>
                <button class="api-button" onclick="testAPI('/api/products')">Liste Produits</button>
                <button class="api-button security-button" onclick="testAPI('/api/security-test')">🔍 Test Sécurité</button>
            </div>
            
            <h3>➕ Ajouter un Produit</h3>
            <div class="product-form">
                <input type="text" class="form-input" id="productName" placeholder="Nom du produit">
                <input type="number" class="form-input" id="productPrice" placeholder="Prix" step="0.01">
                <input type="text" class="form-input" id="productCategory" placeholder="Catégorie">
                <button class="api-button" onclick="createProduct()">Créer Produit</button>
            </div>
            
            <div class="response-area" id="response">
Cliquez sur un bouton pour tester les APIs...
            </div>
        </div>
        
        <div class="features">
            <div class="feature">
                <h3>🐘 PHP Native</h3>
                <p>Application PHP pure sans framework</p>
            </div>
            <div class="feature">
                <h3>🔓 Accès Direct</h3>
                <p>Pas de reverse proxy pour les pentests</p>
            </div>
            <div class="feature">
                <h3>🔍 Test Sécurité</h3>
                <p>Endpoints spéciaux pour les tests</p>
            </div>
            <div class="feature">
                <h3>🛡️ DevSecOps</h3>
                <p>Environnement de test sécurisé</p>
            </div>
        </div>
        
        <p style="margin-top: 2rem; font-size: 0.9rem; opacity: 0.8;">
            Accès Direct - Port 8000 (Pas de Nginx Reverse Proxy)
        </p>
    </div>

    <script>
        async function testAPI(endpoint) {
            const responseArea = document.getElementById('response');
            responseArea.textContent = 'Chargement...';
            
            try {
                const response = await fetch(endpoint);
                const data = await response.json();
                
                responseArea.textContent = `Status: ${response.status}\n\n${JSON.stringify(data, null, 2)}`;
            } catch (error) {
                responseArea.textContent = `Erreur: ${error.message}`;
            }
        }
        
        async function createProduct() {
            const name = document.getElementById('productName').value;
            const price = document.getElementById('productPrice').value;
            const category = document.getElementById('productCategory').value;
            const responseArea = document.getElementById('response');
            
            if (!name || !price) {
                responseArea.textContent = 'Erreur: Nom et prix requis';
                return;
            }
            
            responseArea.textContent = 'Création en cours...';
            
            try {
                const response = await fetch('/api/products', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name, price: parseFloat(price), category })
                });
                
                const data = await response.json();
                responseArea.textContent = `Status: ${response.status}\n\n${JSON.stringify(data, null, 2)}`;
                
                if (response.ok) {
                    document.getElementById('productName').value = '';
                    document.getElementById('productPrice').value = '';
                    document.getElementById('productCategory').value = '';
                }
            } catch (error) {
                responseArea.textContent = `Erreur: ${error.message}`;
            }
        }
        
        // Test de connectivité au chargement
        window.onload = function() {
            testAPI('/api/health');
        };
    </script>
</body>
</html>
    <?php
}
?>

