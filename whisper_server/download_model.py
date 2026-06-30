from faster_whisper.utils import download_model
import os

model_size = os.environ.get("WHISPER_MODEL_SIZE", "medium")
output_dir = os.environ.get("MODEL_DIR", "/var/www/bps/whisper_server/models")
print(f"Downloading {model_size} model to {output_dir}...")
download_model(model_size, output_dir=output_dir)
print("Done!")
