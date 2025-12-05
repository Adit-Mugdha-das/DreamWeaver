import pandas as pd
import matplotlib.pyplot as plt
import numpy as np

# Load datasets
files = {
    'Drop-off': 'realistic_user_data_part1_dropoffs.csv',
    'Moderate': 'realistic_user_data_part2_moderate_users_with_stories_extended_dna_8types (1).csv',
    'High Engagement': 'realistic_user_data_part3_high_engagement_with_stories_dna_8types.csv'
}

dfs = []
for group, file in files.items():
    try:
        df = pd.read_csv(file)
        df['Group'] = group
        dfs.append(df)
    except FileNotFoundError:
        print(f"Warning: {file} not found.")

if not dfs:
    print("No data loaded.")
    exit()

combined_df = pd.concat(dfs, ignore_index=True)

# 1. Engagement Funnel (Bar Chart)
plt.figure(figsize=(8, 6))
group_counts = combined_df['Group'].value_counts().reindex(['Drop-off', 'Moderate', 'High Engagement'])
bars = plt.bar(group_counts.index, group_counts.values, color=['#ff9999', '#66b3ff', '#99ff99'])
plt.title('User Engagement Funnel')
plt.xlabel('User Group')
plt.ylabel('Number of Users')
plt.grid(axis='y', linestyle='--', alpha=0.7)
for bar in bars:
    height = bar.get_height()
    plt.text(bar.get_x() + bar.get_width()/2., height,
             f'{int(height)}',
             ha='center', va='bottom')
plt.savefig('chart_engagement_funnel.png')
print("Generated chart_engagement_funnel.png")

# 2. Activity Boxplot (Total Dreams by Group)
plt.figure(figsize=(10, 6))
data_to_plot = [combined_df[combined_df['Group'] == g]['Total_Dreams'] for g in ['Drop-off', 'Moderate', 'High Engagement']]
plt.boxplot(data_to_plot, labels=['Drop-off', 'Moderate', 'High Engagement'])
plt.title('Distribution of Total Dreams Logged by Group')
plt.ylabel('Total Dreams')
plt.grid(axis='y', linestyle='--', alpha=0.7)
plt.savefig('chart_activity_boxplot.png')
print("Generated chart_activity_boxplot.png")

# 3. Riddle Difficulty Scatter (High Engagement only)
high_eng_df = combined_df[combined_df['Group'] == 'High Engagement']
plt.figure(figsize=(8, 6))
plt.scatter(high_eng_df['Riddles_Attempted'], high_eng_df['Riddles_Solved'], alpha=0.6, c='purple')
plt.plot([0, max(high_eng_df['Riddles_Attempted'])], [0, max(high_eng_df['Riddles_Attempted'])], 'r--', label='100% Success Line')
plt.title('Riddle Difficulty: Attempts vs. Solved (High Engagement)')
plt.xlabel('Riddles Attempted')
plt.ylabel('Riddles Solved')
plt.legend()
plt.grid(True, alpha=0.3)
plt.savefig('chart_riddle_difficulty.png')
print("Generated chart_riddle_difficulty.png")

# 4. Correlation Heatmap (High Engagement)
# Select numerical columns relevant to "Ecosystem Effect"
cols = ['Total_Dreams', 'Evolution_Score', 'Active_Days', 'Riddles_Solved', 'Total_Story_Chapters', 'Avg_Session_Minutes']
corr_matrix = high_eng_df[cols].corr()

plt.figure(figsize=(10, 8))
im = plt.imshow(corr_matrix, cmap='coolwarm', interpolation='nearest')
plt.colorbar(im)
plt.xticks(range(len(cols)), cols, rotation=45, ha='right')
plt.yticks(range(len(cols)), cols)
plt.title('Correlation Matrix (High Engagement Users)')

# Loop over data dimensions and create text annotations.
for i in range(len(cols)):
    for j in range(len(cols)):
        text = plt.text(j, i, f"{corr_matrix.iloc[i, j]:.2f}",
                       ha="center", va="center", color="black")

plt.tight_layout()
plt.savefig('chart_correlation_heatmap.png')
print("Generated chart_correlation_heatmap.png")
