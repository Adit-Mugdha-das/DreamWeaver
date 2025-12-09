import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns

# Read the data
df = pd.read_csv('combined_user_data_NEW.csv')

# Define key features to analyze (binary: used or not used)
features = {
    'Dreams': 'Total_Dreams',
    'Emotions': 'Dreams_With_Emotions',
    'DNA Viewed': 'DNA_Viewed',
    'Art Generated': 'Art_Generated',
    'Mindmaps': 'Mindmaps_Created',
    'Totems': 'Totems_Collected',
    'Riddles': 'Riddles_Solved',
    'Story Mode': 'Total_Story_Chapters',
    'Avatar': 'Avatar_Created',
    'Messages': 'Messages_Sent',
    'Library': 'Library_Texts_Viewed'
}

# Create binary matrix (1 = feature used, 0 = not used)
binary_matrix = pd.DataFrame()

for feature_name, column in features.items():
    if column in ['DNA_Viewed', 'Avatar_Created']:
        # Yes/No columns
        binary_matrix[feature_name] = (df[column] == 'Yes').astype(int)
    else:
        # Numeric columns - 1 if > 0, else 0
        binary_matrix[feature_name] = (df[column] > 0).astype(int)

# Calculate feature co-occurrence matrix (correlation)
# This shows how often features are used together
cooccurrence = binary_matrix.T.dot(binary_matrix)

# Convert to percentage of total users
cooccurrence_pct = (cooccurrence / len(df)) * 100

# Create figure
fig, ax = plt.subplots(figsize=(12, 10), dpi=300)

# Create heatmap
sns.heatmap(cooccurrence_pct, annot=True, fmt='.0f', cmap='YlGnBu',
            linewidths=0.5, linecolor='gray',
            cbar_kws={'label': 'Co-occurrence (% of users)'},
            square=True, ax=ax,
            vmin=0, vmax=100,
            annot_kws={'fontsize': 9, 'fontweight': 'bold'})

# Customize plot
ax.set_title('Feature Co-occurrence Matrix\n(Percentage of Users Using Feature Pairs)', 
             fontsize=14, fontweight='bold', fontname='Arial', pad=20)
ax.set_xlabel('Features', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_ylabel('Features', fontsize=12, fontweight='bold', fontname='Arial')

# Rotate labels for better readability
plt.xticks(rotation=45, ha='right', fontsize=10, fontname='Arial')
plt.yticks(rotation=0, fontsize=10, fontname='Arial')

# Add statistics box
total_features = len(features)
feature_usage = binary_matrix.sum(axis=1).mean()
most_used = binary_matrix.sum().idxmax()
most_used_pct = (binary_matrix.sum().max() / len(df)) * 100
least_used = binary_matrix.sum().idxmin()
least_used_pct = (binary_matrix.sum().min() / len(df)) * 100

stats_text = f'Total Features: {total_features}\n' \
             f'Avg features/user: {feature_usage:.1f}\n\n' \
             f'Most used:\n  {most_used} ({most_used_pct:.0f}%)\n\n' \
             f'Least used:\n  {least_used} ({least_used_pct:.0f}%)'

ax.text(1.28, 0.5, stats_text, transform=ax.transAxes,
        fontsize=9, fontname='Arial',
        verticalalignment='center',
        bbox=dict(boxstyle='round', facecolor='lightyellow', alpha=0.9, 
                 edgecolor='darkorange', linewidth=1.5))

# Add interpretation box
interpretation = 'Feature Clustering:\n\n' \
                 '• Diagonal = individual\n' \
                 '  feature usage\n\n' \
                 '• Darker cells = features\n' \
                 '  often used together\n\n' \
                 '• Lighter cells = features\n' \
                 '  rarely combined'

ax.text(1.28, 0.95, interpretation, transform=ax.transAxes,
        fontsize=8, fontname='Arial', style='italic',
        verticalalignment='top',
        bbox=dict(boxstyle='round', facecolor='lightblue', alpha=0.8, 
                 edgecolor='navy', linewidth=1.5))

# Adjust layout
plt.tight_layout()

# Save figure
plt.savefig('CHART_15_Feature_Cooccurrence.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_15_Feature_Cooccurrence.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart 15 generated successfully!")
print(f"\nFeature Co-occurrence Matrix:")
print(f"\nTotal Features Analyzed: {total_features}")
print(f"Total Users: N = {len(df)}")
print(f"Average features per user: {feature_usage:.1f}")
print(f"\nFeature Usage:")
for feature_name in binary_matrix.columns:
    usage_count = binary_matrix[feature_name].sum()
    usage_pct = (usage_count / len(df)) * 100
    print(f"  {feature_name}: {usage_count} users ({usage_pct:.1f}%)")
print(f"\nMost used feature: {most_used} ({most_used_pct:.0f}% of users)")
print(f"Least used feature: {least_used} ({least_used_pct:.0f}% of users)")
print(f"\nKey Insights:")
print(f"  • Matrix shows which features are commonly used together")
print(f"  • Diagonal values = individual feature adoption rates")
print(f"  • Off-diagonal values = co-occurrence patterns")
print(f"  • Darker cells indicate strong feature clustering")
print(f"\nFiles saved:")
print(f"  - CHART_15_Feature_Cooccurrence.png (300 DPI)")
print(f"  - CHART_15_Feature_Cooccurrence.pdf (300 DPI)")

# Don't show interactive plot, just save
# plt.show()
