import sys
from PySide6.QtUiTools import QUiLoader
from PySide6.QtWidgets import QApplication, QMessageBox
from PySide6.QtCore import QFile, QIODevice
import requests
import qrcode
import json

def process():
    data = {
        "editorName": window.editor.text(), 
        "choosenBox": window.postalBox.currentData(),
        "choosenBoxName": window.postalBox.currentText(),
        "senderName": window.name.text(),
        "senderStreet": window.street.text(),
        "senderTown": window.town.text(),
        "senderPostalCode": window.postalCode.text()
    }
    json_data = json.dumps(data, ensure_ascii=False)
    img = qrcode.make(json_data)
    img.save("shipment_qr_code.jpg")    


if __name__ == "__main__":
    app = QApplication(sys.argv)

    ui_file_name = "postalCodes.ui"
    ui_file = QFile(ui_file_name)
    if not ui_file.open(QIODevice.ReadOnly):
        msgBox = QMessageBox()
        msgBox.setText("Nepodařilo se najít konfugurační soubor.")
        msgBox.setWindowTitle("Došlo k chybě")
        ret = msgBox.exec()
        sys.exit(-1)

    loader = QUiLoader()
    window = loader.load(ui_file)
    ui_file.close()
    if not window:
        msgBox = QMessageBox()
        msgBox.setText("Nepodařilo se načíst konfugurační soubor.")
        msgBox.setWindowTitle("Došlo k chybě")
        ret = msgBox.exec()
        sys.exit(-1)

    window.show()
    window.generateBtn.clicked.connect(process)

    try:
        response = requests.get("http://vmi2406914.contaboserver.net:8001/")
        response.raise_for_status()
        data = response.json()
        for key, name in data.items():
            window.postalBox.addItem(name, key)

    except Exception as e:
        msgBox = QMessageBox()
        msgBox.setText("Došlo k chybě při čtení dat ze serveru.")
        msgBox.setWindowTitle("Došlo k chybě")
        ret = msgBox.exec() 
        sys.exit(-1)  

    sys.exit(app.exec())

