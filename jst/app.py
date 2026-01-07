from flask import Flask, render_template, request
import numpy as np
import matplotlib.pyplot as plt
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense
from tensorflow.keras.optimizers import SGD
import os
import time

os.makedirs('static', exist_ok=True)

app = Flask(__name__)

def plot_exists(filename):
    return os.path.exists(os.path.join(app.static_folder, filename))

@app.context_processor
def utility_processor():
    return dict(plot_exists=plot_exists, timestamp=int(time.time()))

def build_model():
    model = Sequential()
    model.add(Dense(4, input_dim=2, activation='relu'))
    model.add(Dense(1, activation='sigmoid'))

    model.compile(loss='binary_crossentropy',
                  optimizer=SGD(learning_rate=0.1),
                  metrics=['accuracy'])

    # Dataset XOR
    X = np.array([[0, 0], [0, 1], [1, 0], [1, 1]])
    y = np.array([[0], [1], [1], [0]])

    # Latih model
    history = model.fit(X, y, epochs=200, verbose=0)

    # Simpan grafik loss dan accuracy
    plt.figure(figsize=(10, 5))

    # Plot Loss
    plt.subplot(1, 2, 1)
    plt.plot(history.history['loss'], label='Loss', color='red')
    plt.title('Training Loss')
    plt.xlabel('Epoch')
    plt.ylabel('Loss')
    plt.grid()
    plt.legend()

    # Plot Accuracy
    plt.subplot(1, 2, 2)
    plt.plot(history.history['accuracy'], label='Accuracy', color='blue')
    plt.title('Training Accuracy')
    plt.xlabel('Epoch')
    plt.ylabel('Accuracy')
    plt.grid()
    plt.legend()

    plt.tight_layout()
    plt.savefig('static/training_plot.png')
    plt.close()

    return model


model = build_model()

@app.route('/', methods=['GET', 'POST'])
def index():
    result = None
    calc_steps = ""
    input1 = ''
    input2 = ''
    
    if request.method == 'POST':
        try:
            input1 = int(request.form['input1'])
            input2 = int(request.form['input2'])
            
            # Validasi input
            if input1 not in [0, 1] or input2 not in [0, 1]:
                raise ValueError("Input harus 0 atau 1")
                
            # Prediksi
            input_data = np.array([[input1, input2]])
            prediction = model.predict(input_data, verbose=0)[0][0]
            rounded = round(prediction)

            calc_steps = f"""
            <b>Langkah perhitungan:</b><br>
            Input: [{input1}, {input2}]<br>
            Nilai prediksi: {prediction:.4f}<br>
            Hasil bulat: {rounded}<br><br>
            <small>Catatan: Output > 0.5 dibulatkan ke 1, ≤ 0.5 dibulatkan ke 0</small>
            """
            
            result = f"Hasil XOR: {rounded} (nilai prediksi: {prediction:.4f})"
            
        except ValueError as ve:
            result = f"Error: {str(ve)}"
        except Exception as e:
            result = "Terjadi error dalam pemrosesan"

    return render_template('index.html', 
                         result=result, 
                         calc_steps=calc_steps, 
                         input1=input1, 
                         input2=input2)

if __name__ == '__main__':
    app.run(debug=True)