import os
import time
import dropbox
from dropbox import DropboxOAuth2FlowNoRedirect

#if ever mag iba ung access renew lng sa dropbox
TOKEN_FILE = 'access_token.txt'

def authenticate_dropbox_oauth():
    APP_KEY = '3pms6miqqdm0p9r'
    APP_SECRET = '1e0loz2tlln027w'

    auth_flow = DropboxOAuth2FlowNoRedirect(APP_KEY, APP_SECRET)

    authorize_url = auth_flow.start()
    print("1. Go to: " + authorize_url)
    print("2. Click 'Allow' (you might need to log in).")
    print("3. Copy the authorization code.")

    auth_code = input("Enter the authorization code here: ")

    auth_result = auth_flow.finish(auth_code)
    access_token = auth_result.access_token
    print("Access token:", access_token)

    with open(TOKEN_FILE, 'w') as token_file:
        token_file.write(access_token)

    return access_token

def upload_file_to_dropbox(file_path, dropbox_path, access_token):
    dbx = dropbox.Dropbox(access_token)

    if not os.path.exists(file_path):
        print(f"Error: Local file {file_path} does not exist.")
        return

    try:
        with open(file_path, "rb") as f:
            dbx.files_upload(f.read(), dropbox_path, mode=dropbox.files.WriteMode('overwrite'), mute=True)
            print(f"Uploaded: {file_path} to {dropbox_path}")
    except dropbox.exceptions.ApiError as e:
        print(f"Failed to upload {file_path}: {e}")

def main():
    # Get access token
    if os.path.exists(TOKEN_FILE):
        with open(TOKEN_FILE, 'r') as token_file:
            access_token = token_file.read().strip()
            print("Access token loaded from file.")
    else:
        access_token = authenticate_dropbox_oauth()

    # Define file paths
    files_to_upload = {
        'live-monitor/config.ini': '/live-monitor/config.ini',
        'live-monitor/ph_log_live.txt': '/live-monitor/ph_log_live.txt',
        'live-monitor/ph_log.txt': '/live-monitor/ph_log.txt',
        'live-monitor/temperature_log_live.txt': '/live-monitor/temperature_log_live.txt',
        'live-monitor/temperature_log.txt': '/live-monitor/temperature_log.txt',
    }

    for local_path, dropbox_path in files_to_upload.items():
        upload_file_to_dropbox(local_path, dropbox_path, access_token)

if __name__ == '__main__':
    try:
        while True:
            print(f"Starting upload cycle at {time.strftime('%Y-%m-%d %H:%M:%S')}")
            main()
            print(f"Upload cycle completed. Next cycle starts in 5 seconds.\n")
            time.sleep(5)
    except KeyboardInterrupt:
        print("Script interrupted. Exiting...")
