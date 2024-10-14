import bcrypt
from models import User

def hash_password(password):
    """Hash the password using bcrypt."""
    return bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt())

def verify_password(stored_password, provided_password):
    """Verify stored password against provided password."""
    return bcrypt.checkpw(provided_password.encode('utf-8'), stored_password.encode('utf-8'))

def authenticate(username, password):
    """Authenticate user with username and password."""
    try:
        user = User.get(User.username == username)
        if verify_password(user.password, password):
            return user
        else:
            return None
    except User.DoesNotExist:
        return None
