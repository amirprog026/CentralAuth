from flask import Flask, request, jsonify
from models import User, UserMeta
from utils import hash_password, authenticate

app = Flask(__name__)

@app.route('/register', methods=['POST'])
def register():
    data = request.json
    platform = data.get('platform')
    username = data.get('username')
    password = data.get('password')

    if not all([platform, username, password]):
        return jsonify({'error': 'Missing data'}), 400

    hashed_password = hash_password(password)
    try:
        user = User.create(platform=platform, username=username, password=hashed_password)
        return jsonify({'message': 'User registered successfully', 'user_id': user.id}), 201
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/login', methods=['POST'])
def login():
    data = request.json
    username = data.get('username')
    password = data.get('password')

    user = authenticate(username, password)
    if user:
        return jsonify({'message': 'Login successful', 'user_id': user.id}), 200
    else:
        return jsonify({'error': 'Invalid credentials'}), 401

@app.route('/add_meta', methods=['POST'])
def add_meta():
    data = request.json
    user_id = data.get('user_id')
    key = data.get('key')
    value = data.get('value')

    if not all([user_id, key, value]):
        return jsonify({'error': 'Missing data'}), 400

    try:
        user = User.get_by_id(user_id)
        UserMeta.create(user=user, key=key, value=value)
        return jsonify({'message': 'Meta data added successfully'}), 201
    except User.DoesNotExist:
        return jsonify({'error': 'User not found'}), 404
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
