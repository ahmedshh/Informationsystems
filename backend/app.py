from flask import Flask, request, jsonify
from flask_jwt_extended import JWTManager, create_access_token, jwt_required, get_jwt_identity
from flask_cors import CORS
from werkzeug.security import generate_password_hash, check_password_hash
import mysql.connector

# Initialize Flask app
app = Flask(__name__)
CORS(app)  # Enable CORS for cross-origin requests
app.config["JWT_SECRET_KEY"] = "your_jwt_secret_key"  # Replace with a secure secret key
jwt = JWTManager(app)

# MySQL database configuration
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',  # Replace with your MySQL root password if set
    'database': 'ehr_system'
}

# Establish database connection
def get_db_connection():
    return mysql.connector.connect(**db_config)

# -------------------- Routes -------------------- #

# Welcome route
@app.route("/", methods=["GET"])
def welcome():
    return "Welcome to the EHR Backend!"

# Register a new doctor
@app.route("/register", methods=["POST"])
def register():
    data = request.json
    name = data.get("name")
    email = data.get("email")
    password = data.get("password")

    if not name or not email or not password:
        return jsonify({"error": "All fields are required!"}), 400

    hashed_password = generate_password_hash(password, method="pbkdf2:sha256")
    try:
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute(
            "INSERT INTO doctors (name, email, password) VALUES (%s, %s, %s)",
            (name, email, hashed_password)
        )
        conn.commit()
        return jsonify({"message": "Successfully registered!"}), 201
    except mysql.connector.IntegrityError:
        return jsonify({"error": "Email already exists!"}), 400
    except Exception as e:
        return jsonify({"error": str(e)}), 500
    finally:
        cursor.close()
        conn.close()

# Doctor login
@app.route("/login", methods=["POST"])
def login():
    data = request.json
    email = data.get("email")
    password = data.get("password")

    if not email or not password:
        return jsonify({"error": "Email and password are required!"}), 400

    try:
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("SELECT id, password FROM doctors WHERE email = %s", (email,))
        doctor = cursor.fetchone()
        if doctor and check_password_hash(doctor[1], password):
            token = create_access_token(identity={"doctor_id": doctor[0]})
            return jsonify({"access_token": token}), 200
        else:
            return jsonify({"error": "Invalid email or password!"}), 401
    except Exception as e:
        return jsonify({"error": str(e)}), 500
    finally:
        cursor.close()
        conn.close()

# Add a new patient
@app.route("/patients", methods=["POST"])
@jwt_required()
def add_patient():
    data = request.json
    print("Received patient data:", data)  # Debugging the data received

    name = data.get("name")
    age = data.get("age")
    medical_history = data.get("medical_history")
    doctor_id = get_jwt_identity()["doctor_id"]

    if not name or not isinstance(name, str):
        return jsonify({"error": "Invalid or missing name"}), 422
    if not age or not isinstance(age, int):
        return jsonify({"error": "Invalid or missing age"}), 422
    if not medical_history or not isinstance(medical_history, str):
        return jsonify({"error": "Invalid or missing medical history"}), 422

    try:
        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute(
            "INSERT INTO patients (name, age, medical_history, doctor_id) VALUES (%s, %s, %s, %s)",
            (name, age, medical_history, doctor_id)
        )
        conn.commit()
        return jsonify({"message": "Patient added successfully!"}), 201
    except Exception as e:
        return jsonify({"error": str(e)}), 500
    finally:
        cursor.close()
        conn.close()

# Get all patients for the logged-in doctor
@app.route("/patients", methods=["GET"])
@jwt_required()
def get_patients():
    doctor_id = get_jwt_identity()["doctor_id"]

    try:
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT id, name, age, medical_history FROM patients WHERE doctor_id = %s", (doctor_id,))
        patients = cursor.fetchall()

        print("Fetched patients:", patients)  # Debugging
        return jsonify(patients), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500
    finally:
        cursor.close()
        conn.close()

# -------------------- Main -------------------- #

if __name__ == "__main__":
    app.run(debug=True)
