#!/bin/bash

# NADIA - Script de Despliegue Automático
# Servidor: derecho.unap.edu.pe
# Ruta: /mnt/web_data/www/html/derecho.unap.edu.pe/public_html/sespecialidad/nadia/gemini/

TARGET_DIR="/mnt/web_data/www/html/derecho.unap.edu.pe/public_html/sespecialidad/nadia/gemini"

echo "--------------------------------------------------"
echo "🚀 Iniciando Despliegue de NADIA (Versión Actualizada)"
echo "--------------------------------------------------"

if [ -d "$TARGET_DIR" ]; then
    cd "$TARGET_DIR"
    
    echo "📥 Descargando cambios desde GitHub..."
    # Se asume que el repositorio ya está configurado con 'origin main'
    git pull origin main
    
    echo "🔑 Ajustando permisos de carpetas..."
    # Permisos generales para la carpeta pública
    chmod -R 755 public/
    
    # Permisos de escritura para la carpeta de subida de archivos
    chmod -R 777 public/uploads/
    
    echo "✅ Despliegue finalizado con éxito."
    echo "🌐 Visita: https://derecho.unap.edu.pe/sespecialidad/nadia/gemini/public/"
else
    echo "❌ ERROR: El directorio $TARGET_DIR no fue encontrado."
    echo "Asegúrate de ejecutar este script en el servidor correcto."
    exit 1
fi
