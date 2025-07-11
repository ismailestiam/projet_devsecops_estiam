from flask import Flask, render_template, request, jsonify
from flask_cors import CORS
import os

app = Flask(__name__)
CORS(app)

# Configuration Stripe (clés de test)
STRIPE_PUBLISHABLE_KEY = os.getenv('STRIPE_PUBLISHABLE_KEY', 'pk_test_51234567890abcdef')
STRIPE_SECRET_KEY = os.getenv('STRIPE_SECRET_KEY', 'sk_test_51234567890abcdef')

@app.route('/')
def index():
    return render_template('index.html', stripe_publishable_key=STRIPE_PUBLISHABLE_KEY)

@app.route('/api/stripe/config')
def get_stripe_config():
    return jsonify({
        'publishable_key': STRIPE_PUBLISHABLE_KEY
    })

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

@app.route('/health')
def health():
    return jsonify({
        'status': 'OK',
        'service': 'Stripe Payment API',
        'stripe_configured': bool(STRIPE_PUBLISHABLE_KEY)
    })

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=False)

