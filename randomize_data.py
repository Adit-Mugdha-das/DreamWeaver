import pandas as pd
import numpy as np
import os

files = [
    'realistic_user_data_part1_dropoffs.csv',
    'realistic_user_data_part2_moderate_users_with_stories_extended_dna_8types (1).csv',
    'realistic_user_data_part3_high_engagement_with_stories_dna_8types.csv'
]

# Map user friendly names to actual CSV columns
columns_to_perturb = {
    'Total_Dreams': {'type': 'int', 'range': 1},
    'Total_Logins': {'type': 'int', 'range': 2},
    'Total_Story_Chapters': {'type': 'int', 'range': 2},
    'Dreams_With_Story': {'type': 'int', 'range': 1},
    'Evolution_Score': {'type': 'int', 'range': 4},
    'Health_Score': {'type': 'int', 'range': 4},
    'Avg_Session_Minutes': {'type': 'int', 'range': 5},
    'Likes_Given': {'type': 'int', 'range': 2},
    'Comments_Given': {'type': 'int', 'range': 1},
    'Messages_Sent': {'type': 'int', 'range': 1},
    'Features_Used_Count': {'type': 'int', 'range': 1}
}

def perturb_value(val, range_val, min_val=0, max_val=None):
    if pd.isna(val):
        return val
    
    # Handle string numbers or percentages if any
    if isinstance(val, str):
        try:
            val = float(val.replace('%', ''))
        except:
            return val
            
    noise = np.random.randint(-range_val, range_val + 1)
    new_val = val + noise
    
    if min_val is not None:
        new_val = max(min_val, new_val)
    if max_val is not None:
        new_val = min(max_val, new_val)
        
    return new_val

for file_name in files:
    if not os.path.exists(file_name):
        print(f"File not found: {file_name}")
        continue
        
    print(f"Processing {file_name}...")
    try:
        df = pd.read_csv(file_name)
        
        # Apply perturbations
        for col, settings in columns_to_perturb.items():
            if col in df.columns:
                max_val = 100 if 'Score' in col else None
                df[col] = df[col].apply(lambda x: perturb_value(x, settings['range'], min_val=0, max_val=max_val))
                
        # Consistency checks
        if 'Total_Dreams' in df.columns and 'Dreams_With_Emotions' in df.columns:
            df['Dreams_With_Emotions'] = df.apply(lambda row: min(row['Dreams_With_Emotions'], row['Total_Dreams']), axis=1)
            
        if 'Total_Dreams' in df.columns and 'Dreams_With_Story' in df.columns:
             df['Dreams_With_Story'] = df.apply(lambda row: min(row['Dreams_With_Story'], row['Total_Dreams']), axis=1)

        if 'Active_Days' in df.columns and 'Total_Logins' in df.columns:
            df['Total_Logins'] = df.apply(lambda row: max(row['Total_Logins'], row['Active_Days']), axis=1)

        # Save back
        try:
            df.to_csv(file_name, index=False)
            print(f"Saved {file_name}")
        except PermissionError:
            new_name = file_name.replace('.csv', '_randomized.csv')
            df.to_csv(new_name, index=False)
            print(f"Permission denied for {file_name}. Saved as {new_name}")
            
    except Exception as e:
        print(f"Error processing {file_name}: {e}")

print("Done.")
