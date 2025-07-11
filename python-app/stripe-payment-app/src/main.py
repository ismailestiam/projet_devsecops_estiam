import os
import sys
# DON'T CHANGE THIS !!!
sys.path.insert(0, os.path.dirname(os.path.dirname(__file__)))

from flask import Flask, send_from_directory, request, jsonify
from flask_cors import CORS
from src.models.user import db
from src.routes.user import user_bp

app = Flask(__name__, static_folder=os.path.join(os.path.dirname(__file__), 'static'))
app.config['SECRET_KEY'] = 'asdf#FGSgvasgf$5$WGT'

# Activation de CORS pour toutes les routes
CORS(app)

app.register_blueprint(user_bp, url_prefix='/api')

# Configuration Stripe (clés de test)
STRIPE_PUBLISHABLE_KEY = "pk_test_51234567890abcdef"
STRIPE_SECRET_KEY = "sk_test_51234567890abcdef"

# Route pour obtenir la clé publique Stripe
@app.route('/api/stripe/config')
def get_stripe_config():
    return jsonify({
        'publishable_key': STRIPE_PUBLISHABLE_KEY
    })

# Route pour créer un intent de paiement (simulation)
@app.route('/api/stripe/create-payment-intent', methods=['POST'])
def create_payment_intent():
    try:
        data = request.get_json()
        amount = data.get('amount', 1000)  # Montant en centimes
        
        # Simulation d'une réponse Stripe
        return jsonify({
            'client_secret': 'pi_test_1234567890_secret_abcdef',
            'amount': amount,
            'currency': 'eur',
            'status': 'requires_payment_method'
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 400

# Route pour confirmer un paiement (simulation)
@app.route('/api/stripe/confirm-payment', methods=['POST'])
def confirm_payment():
    try:
        data = request.get_json()
        payment_intent_id = data.get('payment_intent_id')
        
        # Simulation d'une confirmation de paiement
        return jsonify({
            'payment_intent_id': payment_intent_id,
            'status': 'succeeded',
            'amount_received': 1000,
            'currency': 'eur'
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 400

# uncomment if you need to use database
app.config['SQLALCHEMY_DATABASE_URI'] = f"sqlite:///{os.path.join(os.path.dirname(__file__), 'database', 'app.db')}"
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db.init_app(app)
with app.app_context():
    db.create_all()

@app.route('/', defaults={'path': ''})
@app.route('/<path:path>')
def serve(path):
    static_folder_path = app.static_folder
    if static_folder_path is None:
            return "Static folder not configured", 404

    if path != "" and os.path.exists(os.path.join(static_folder_path, path)):
        return send_from_directory(static_folder_path, path)
    else:
        index_path = os.path.join(static_folder_path, 'index.html')
        if os.path.exists(index_path):
            return send_from_directory(static_folder_path, 'index.html')
        else:
            return "index.html not found", 404


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
