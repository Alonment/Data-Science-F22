import requests
import h5py
import datetime

import pandas as pd


if __name__ == "__main__":

    """DATASET I: DISCORD DATA"""
    
    # Load discord data as csv
    df_discord = pd.read_csv("../Assignment2/Assignment2_Data.csv", index_col=0)

    # Write discord data csv as an hdf5 group with the key "data" 
    df_discord.to_hdf("DiscordData.hdf5", key="data", mode="w", index=False)

    # Write the metadata from the txt file as key-value pairs in the "data" group's corresponding attributes
    with h5py.File('DiscordData.hdf5', 'a') as hdf5_file:        
        with open('../Assignment2/Assignment2_MetaData.txt', 'r') as metadata:

            for line in metadata:
                key, val = line.strip().split(":", maxsplit=1)
                hdf5_file['data'].attrs[key] = val

        # Store the POSIX timestamp for the exact moment this hdf5 was created
        hdf5_file['data'].attrs["TIME_CREATED"] = datetime.datetime.now().timestamp()


    """DATASET II: FORGE FAILURE DATA"""

    URL = "https://t0tfudyw7g.execute-api.us-east-1.amazonaws.com/main/get-failures?devtest8869235418903039=0"
    
    # Send a get request to the necessary endpoint to retrieve the data
    req = requests.get(URL)

    # Load the request as a json file, cast into a dictionary and convert into a dataframe
    file = req.json()
    failures = file['failures']
    df_forge = pd.DataFrame.from_dict(failures)

    # Write forge failure data as an hdf5 file with the key "data"
    df_forge.to_hdf("ForgeFailureData.hdf5", key="data", mode="w", index=False)

    # Store the POSIX timestamp for the exact moment this hdf5 was created
    with h5py.File('ForgeFailureData.hdf5', 'a') as f:
        f['data'].attrs["TIME_CREATED"] = datetime.datetime.now().timestamp()
