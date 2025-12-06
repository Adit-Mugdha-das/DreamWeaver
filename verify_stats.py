import pandas as pd
import numpy as np
from scipy import stats

# Load data
df = pd.read_csv(r'c:\xampp\htdocs\dreamweaver\combined_user_data_NEW.csv')

print("="*80)
print("SECTION 1: Sample & Segmentation")
print("="*80)
print(f"Total users: {len(df)}")
print(f"\nUser segments:")
print(df['User_Segment'].value_counts().sort_index())
print(f"\nRetention rate: {df['Retained'].sum() / len(df) * 100:.1f}%")
print(f"Not retained: {(1 - df['Retained'].sum() / len(df)) * 100:.1f}%")

print("\n" + "="*80)
print("SECTION 2: Descriptive Statistics")
print("="*80)
stats_cols = ['Total_Dreams', 'Active_Days', 'Dreams_With_Emotions', 'Dreams_With_Story', 
              'Totems_Collected', 'Riddles_Attempted', 'Riddles_Solved', 
              'Avg_Session_Minutes', 'Total_Logins', 'Features_Used_Count', 'Engagement_Index']

for col in stats_cols:
    mean_val = df[col].mean()
    std_val = df[col].std()
    min_val = df[col].min()
    max_val = df[col].max()
    print(f"\n{col}:")
    print(f"  Mean = {mean_val:.2f}, SD = {std_val:.2f}")
    print(f"  Range = {min_val:.0f} to {max_val:.0f}")
    if col == 'Dreams_With_Story':
        print(f"  Median = {df[col].median():.0f}")

print("\n" + "="*80)
print("SECTION 3: Evolution & Health Scores")
print("="*80)
score_cols = ['Evolution_Score_Day1', 'Evolution_Score', 'Evolution_Score_Change',
              'Health_Score_Day1', 'Health_Score', 'Health_Score_Change']
for col in score_cols:
    print(f"{col}: Mean = {df[col].mean():.2f}, SD = {df[col].std():.2f}")

print("\n" + "="*80)
print("SECTION 4: Segment-Level Differences")
print("="*80)
print("\nEngagement_Index by segment:")
for segment in ['Drop-off', 'Moderate', 'High Engagement']:
    eng_mean = df[df['User_Segment'] == segment]['Engagement_Index'].mean()
    print(f"  {segment}: M = {eng_mean:.2f}")

print("\nTotal_Dreams by segment:")
for segment in ['Drop-off', 'Moderate', 'High Engagement']:
    dreams_mean = df[df['User_Segment'] == segment]['Total_Dreams'].mean()
    print(f"  {segment}: M = {dreams_mean:.2f}")

print("\nEvolution_Score_Change by segment:")
for segment in ['Drop-off', 'Moderate', 'High Engagement']:
    evo_mean = df[df['User_Segment'] == segment]['Evolution_Score_Change'].mean()
    print(f"  {segment}: M = {evo_mean:.2f}")

print("\nHealth_Score_Change by segment:")
for segment in ['Drop-off', 'Moderate', 'High Engagement']:
    health_mean = df[df['User_Segment'] == segment]['Health_Score_Change'].mean()
    print(f"  {segment}: M = {health_mean:.2f}")

print("\n" + "="*80)
print("SECTION 5: Key Correlations (with Engagement_Index)")
print("="*80)
corr_cols = ['Total_Dreams', 'Active_Days', 'Dreams_With_Emotions', 'Dreams_With_Story',
             'Totems_Collected', 'Riddles_Attempted', 'Riddles_Solved', 
             'Avg_Session_Minutes', 'Total_Logins', 'Features_Used_Count',
             'Evolution_Score', 'Health_Score', 'Evolution_Score_Change', 'Health_Score_Change']

print("\nCorrelations with Engagement_Index:")
for col in corr_cols:
    corr = df['Engagement_Index'].corr(df[col])
    print(f"  {col}: r = {corr:.3f}")

print("\nCorrelations with Evolution_Score_Change:")
change_corr_cols = ['Health_Score_Change', 'Features_Used_Count', 'Avg_Session_Minutes',
                    'Active_Days', 'Totems_Collected']
for col in change_corr_cols:
    corr = df['Evolution_Score_Change'].corr(df[col])
    print(f"  {col}: r = {corr:.3f}")

print("\n" + "="*80)
print("SECTION 6: ANOVA - Engagement_Index by Segment")
print("="*80)
dropoff = df[df['User_Segment'] == 'Drop-off']['Engagement_Index']
moderate = df[df['User_Segment'] == 'Moderate']['Engagement_Index']
high_eng = df[df['User_Segment'] == 'High Engagement']['Engagement_Index']

f_stat, p_val = stats.f_oneway(dropoff, moderate, high_eng)
print(f"\nF-statistic: {f_stat:.2f}")
print(f"p-value: {p_val:.10f}")
print(f"\nGroup means:")
print(f"  Drop-off: M = {dropoff.mean():.2f}")
print(f"  Moderate: M = {moderate.mean():.2f}")
print(f"  High Engagement: M = {high_eng.mean():.2f}")
