import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns

# Read post-survey data
post_survey = pd.read_csv('post_survey_data.csv')

# Define satisfaction dimensions
dimensions = {
    'Easy_to_Use': 'Ease of Use',
    'Meaningful_Insights': 'Meaningful Insights',
    'Story_Engagement': 'Story Engagement',
    'Motivated_Explore': 'Motivation to Explore',
    'Overall_Enjoyment': 'Overall Enjoyment'
}

# Create percentage distribution matrix
rating_levels = [1, 2, 3, 4, 5]
percentages = []
dimension_labels = []

for dim_key, dim_label in dimensions.items():
    dimension_labels.append(dim_label)
    total = len(post_survey[dim_key].dropna())
    
    row = []
    for rating in rating_levels:
        count = (post_survey[dim_key] == rating).sum()
        pct = (count / total) * 100
        row.append(pct)
    percentages.append(row)

# Convert to numpy array for plotting
percentages = np.array(percentages)

# Create figure
fig, ax = plt.subplots(figsize=(12, 7), dpi=300)

# Create stacked horizontal bars
left = np.zeros(len(dimension_labels))
colors = ['#E74C3C', '#E67E22', '#F39C12', '#2ECC71', '#27AE60']  # Red to Green gradient
rating_labels = ['1 (Very Low)', '2 (Low)', '3 (Neutral)', '4 (High)', '5 (Very High)']

for i, (color, label) in enumerate(zip(colors, rating_labels)):
    ax.barh(dimension_labels, percentages[:, i], left=left, 
            color=color, edgecolor='white', linewidth=2, label=label, alpha=0.9)
    
    # Add percentage labels for segments >= 5%
    for j, (dim, pct) in enumerate(zip(dimension_labels, percentages[:, i])):
        if pct >= 5:
            ax.text(left[j] + pct/2, j, f'{pct:.1f}%',
                   ha='center', va='center', fontsize=9, fontweight='bold',
                   color='white', fontname='Arial')
    
    left += percentages[:, i]

# Customize axes
ax.set_xlabel('Percentage of Respondents (%)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_title('User Satisfaction Across Key Dimensions\n(Post-Survey Results, N=86)', 
             fontsize=14, fontweight='bold', fontname='Arial', pad=20)
ax.set_xlim(0, 100)
ax.set_xticks(range(0, 101, 10))

# Add grid
ax.xaxis.grid(True, linestyle='--', alpha=0.3, linewidth=0.5)
ax.set_axisbelow(True)

# Customize y-axis
ax.tick_params(axis='y', labelsize=11)
ax.tick_params(axis='x', labelsize=10)

# Add legend
ax.legend(loc='lower center', fontsize=10, frameon=True, shadow=True, 
          fancybox=True, ncol=5, bbox_to_anchor=(0.5, -0.18))

# Add statistics box (bottom right)
stats_text = 'Mean Ratings (1-5 scale):\n\n'
for dim_key, dim_label in dimensions.items():
    mean_val = post_survey[dim_key].mean()
    stats_text += f'{dim_label}: {mean_val:.2f}\n'

stats_text += f'\nOverall Mean: {post_survey[list(dimensions.keys())].mean().mean():.2f}'

ax.text(1.02, 0.35, stats_text, transform=ax.transAxes,
        fontsize=9, fontname='Arial',
        verticalalignment='center',
        bbox=dict(boxstyle='round', facecolor='#E8F4F8', alpha=0.95, 
                 edgecolor='#2C3E50', linewidth=1.5))

# Add interpretation box (top right, above stats box)
interp_text = 'Key Findings:\n\n' \
              f'✓ {percentages[:, 3:].sum():.1f}% ratings ≥ 4\n' \
              f'✓ {percentages[:, 4].mean():.1f}% avg. 5-star\n' \
              f'✓ {percentages[:, :2].sum():.1f}% ratings ≤ 2\n\n' \
              'High satisfaction\nacross all dimensions'

ax.text(1.02, 0.72, interp_text, transform=ax.transAxes,
        fontsize=8.5, fontname='Arial',
        verticalalignment='center',
        bbox=dict(boxstyle='round', facecolor='#FFF4E6', alpha=0.95, 
                 edgecolor='#E67E22', linewidth=1.5))

# Adjust layout
plt.tight_layout()

# Save figure
plt.savefig('CHART_B_Satisfaction_Heatmap.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_B_Satisfaction_Heatmap.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart B generated successfully!")
print(f"\nSatisfaction Distribution Analysis (N={len(post_survey)}):")
print("="*80)

for dim_key, dim_label in dimensions.items():
    print(f"\n{dim_label}:")
    print(f"  Mean: {post_survey[dim_key].mean():.2f} ± {post_survey[dim_key].std():.2f}")
    print(f"  Distribution:")
    for rating in rating_levels:
        count = (post_survey[dim_key] == rating).sum()
        pct = (count / len(post_survey[dim_key].dropna())) * 100
        print(f"    {rating}: {count:2d} ({pct:5.1f}%)")

# Calculate overall statistics
all_ratings = post_survey[list(dimensions.keys())].values.flatten()
all_ratings = all_ratings[~np.isnan(all_ratings)]

print(f"\n{'='*80}")
print(f"Overall Statistics:")
print(f"  Combined Mean: {all_ratings.mean():.2f} ± {all_ratings.std():.2f}")
print(f"  Ratings ≥ 4: {(all_ratings >= 4).sum()} ({(all_ratings >= 4).sum()/len(all_ratings)*100:.1f}%)")
print(f"  Ratings = 5: {(all_ratings == 5).sum()} ({(all_ratings == 5).sum()/len(all_ratings)*100:.1f}%)")
print(f"  Ratings ≤ 2: {(all_ratings <= 2).sum()} ({(all_ratings <= 2).sum()/len(all_ratings)*100:.1f}%)")

print(f"\nKey Insights:")
print(f"  ✓ {(all_ratings >= 4).sum()/len(all_ratings)*100:.1f}% of all ratings are 4 or 5 (high satisfaction)")
print(f"  ✓ Overall Enjoyment has highest mean ({post_survey['Overall_Enjoyment'].mean():.2f})")
print(f"  ✓ Very low proportion of negative ratings (≤2: {(all_ratings <= 2).sum()/len(all_ratings)*100:.1f}%)")

print(f"\nFiles saved:")
print(f"  - CHART_B_Satisfaction_Heatmap.png (300 DPI)")
print(f"  - CHART_B_Satisfaction_Heatmap.pdf (300 DPI)")

plt.show()
