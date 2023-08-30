from flask import Flask, render_template, request, redirect, url_for, jsonify
from flask_socketio import SocketIO, emit
import sqlite3
import json

app = Flask(__name__)
app.config['SECRET_KEY'] = 'hackerssecretkeynooneknows' 
socketio = SocketIO(app)

con = sqlite3.connect("userdb.db")
cur = con.cursor()
cur.execute("DROP TABLE IF EXISTS users")
cur.execute("CREATE TABLE users (username TEXT, password TEXT)")
cur.execute("INSERT INTO users VALUES ('admin', 'admin')")
cur.execute("INSERT INTO users VALUES ('user', 'user')")

con.commit()
con.close()

@app.route("/")
def home():
    return render_template("index.html")

@app.route("/sqllog", methods=["GET"])
def sqllog():
    with open("log.json", "r") as f:
        data = f.read()
    return data

@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        username = request.form.get("username")
        password = request.form.get("password")
        
        con = sqlite3.connect("userdb.db")
        cur = con.cursor()
        query = "SELECT * FROM users WHERE username = '{}' AND password = '{}'".format(username, password)
        with open("log.json", "a") as f:
            f.write(query + "\n")

        cur.execute(query)
        user_data = cur.fetchone()
        
        con.close()
        
        if user_data:
            return redirect(url_for("dashboard", username=username))
        else:
            return "Invalid username or password. Please try again. <script>setTimeout(function(){window.location.href = '/login';}, 1000);</script>"
    
    return render_template("login.html")

@app.route("/dashboard/<string:username>")
def dashboard(username):
    return render_template("dashboard.html", username=username)
@app.route("/transaction")
def transaction():
    return render_template("transaction.html")


activity_logs = []

@socketio.on('connect')
def handle_connect():
    emit('message', {'data': 'Client connected'})

@socketio.on('disconnect')
def handle_disconnect():
    emit('message', {'data': 'Client disconnected'})

@socketio.on('log_message')
def handle_log_message(data):
    activity_logs.append(data['message'])
    emit('activity_log', {'log': data['message']}, broadcast=True)

if __name__ == '__main__':
    socketio.run(app, debug=True)
