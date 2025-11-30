from flask import Flask, jsonify, request
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

@app.route('/cotizar_seguro', methods=['GET'])
def cotizar_seguro():
    try:
       
        aseguradora = request.args.get('aseguradora', '').lower()
        tipo_pago = request.args.get('tipo_pago', 'contado')
        
   
        precios = {
            'qualitas': 18500,
            'abba': 13700,
            'sura': 11900,
            'general': 15000
        }
        
        nombres = {
            'qualitas': 'Qualitas Seguros',
            'abba': 'Chubb Seguros (Abba)',
            'sura': 'Seguros SURA',
            'general': 'General de Seguros'
        }

        if aseguradora not in precios:
            return jsonify({"status": "error", "mensaje": "Selecciona una aseguradora válida"})

        precio_base = precios[aseguradora]
        nombre_real = nombres[aseguradora]
        
  
        monto_pago = 0
        mensaje_plazo = ""

        if tipo_pago == 'contado':
            monto_pago = precio_base
            mensaje_plazo = "Pago Único Anual"
        elif tipo_pago == 'trimestral':
            monto_pago = precio_base / 4
            mensaje_plazo = "Pago Trimestral"
        elif tipo_pago == 'mensual':
            monto_pago = precio_base / 12
            mensaje_plazo = "Mensualidad (Sumada al crédito)"

        return jsonify({
            "status": "success",
            "aseguradora": nombre_real,
            "monto": round(monto_pago, 2),
            "total_anual": precio_base,
            "plazo": mensaje_plazo
        })

    except Exception as e:
        return jsonify({"status": "error", "mensaje": str(e)})

if __name__ == '__main__':
    print("Servicio de Seguros corriendo en puerto 5000...")
    app.run(port=5000, debug=True)