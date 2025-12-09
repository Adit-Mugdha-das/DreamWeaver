import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

# Read post-survey data
post_survey = pd.read_csv('post_survey_data.csv')

# Get feature preferences
feature_counts = post_survey['Most_Used_Feature'].value_counts()

# Define feature categories for color coding
feature_categories = {
    'Dream DNA': 'Core',
    'Dream Logging': 'Core',
    'Dream Art Generation': 'Creative',
    'Mindmap Tool': 'Creative',
    'Story Mode': 'Narrative',
    'Guided Audio': 'Narrative',
    'Riddle Solving': 'Engagement',
    'Totem Collection': 'Gamification',
    'Community Feed': 'Social',
    'Avatar Customization': 'Social',
    'Dream Library': 'Core'
}

# Color scheme for categories
category_colors = {
    'Core': '#9B59B6',           # Purple
    'Creative': '#E74C3C',       # Red
    'Narrative': '#3498DB',      # Blue
    'Engagement': '#F39C12',     # Orange
    'Gamification': '#2ECC71',   # Green
    'Social': '#1ABC9C'          # Teal
}

# Create figure
fig, ax = plt.subplots(figsize=(12, 8), dpi=300)

# Prepare data
features = feature_counts.index.tolist()
counts = feature_counts.values
percentages = (counts / counts.sum()) * 100

# Assign colors based on category
colors = [category_colors[feature_categories[f]] for f in features]

# Create horizontal bar chart
y_pos = np.arange(len(features))
bars = ax.barh(y_pos, counts, color=colors, edgecolor='black', linewidth=1, alpha=0.85)

# Add count and percentage labels
for i, (count, pct) in enumerate(zip(counts, percentages)):
    ax.text(count + 0.3, i, f'{count} ({pct:.1f}%)',
           va='center', fontsize=10, fontweight='bold', fontname='Arial')

# Customize axes
ax.set_xlabel('Number of Users', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_title('Most Valued Features by Users\n(Post-Survey Results, N=86)', 
             fontsize=14, fontweight='bold', fontname='Arial', pad=20)
ax.set_yticks(y_pos)
ax.set_yticklabels(features, fontsize=11, fontname='Arial')
ax.invert_yaxis()  # Top feature at top

# Add grid
ax.xaxis.grid(True, linestyle='--', alpha=0.3, linewidth=0.5)
ax.set_axisbelow(True)

# Add legend for categories (bottom right)
from matplotlib.patches import Patch
legend_elements = [Patch(facecolor=color, edgecolor='black', label=category)
                   for category, color in category_colors.items()]
ax.legend(handles=legend_elements, loc='lower right', fontsize=9, 
          frameon=True, shadow=True, fancybox=True, title='Feature Category',
          bbox_to_anchor=(1.0, 0.0))

# Add top 3 features box (bottom center, left of category legend)
top3_text = 'Top 3 Features:\n\n'
for i, (feat, count) in enumerate(feature_counts.head(3).items()):
    pct = (count / feature_counts.sum()) * 100
    top3_text += f'{i+1}. {feat}\n   ({count} users, {pct:.1f}%)\n'

ax.text(0.55, 0.02, top3_text, transform=ax.transAxes,
        fontsize=9, fontname='Arial',
        verticalalignment='bottom', horizontalalignment='left',
        bbox=dict(boxstyle='round', facecolor='#E8F4F8', alpha=0.95, 
                 edgecolor='#2C3E50', linewidth=1.5))

# Add insights box
insights_text = 'Key Insights:\n\n' \
                f'✓ {len(feature_counts)} features used\n' \
                f'✓ Core features dominate\n' \
                f'   (DNA, Library, Logging)\n' \
                f'✓ Creative tools popular\n' \
                f'   (Art, Mindmap)\n' \
                f'✓ Engagement features\n' \
                f'   highly valued\n' \
                f'   (Riddles, Story)'

ax.text(0.98, 0.65, insights_text, transform=ax.transAxes,
        fontsize=8.5, fontname='Arial',
        verticalalignment='top', horizontalalignment='right',
        bbox=dict(boxstyle='round', facecolor='#FFF4E6', alpha=0.95, 
                 edgecolor='#E67E22', linewidth=1.5))

# Adjust layout
plt.tight_layout()

# Save figure
plt.savefig('CHART_C_Feature_Preferences.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_C_Feature_Preferences.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart C generated successfully!")
print(f"\nFeature Preference Analysis (N={len(post_survey)}):")
print("="*80)
print(f"\n{'Feature':<25} {'Count':<8} {'Percentage':<12} {'Category':<15}")
print("-"*80)

for feat, count in feature_counts.items():
    pct = (count / feature_counts.sum()) * 100
    category = feature_categories[feat]
    print(f"{feat:<25} {count:<8} {pct:>5.1f}%       {category:<15}")

print(f"\n{'='*80}")
print(f"Category Breakdown:")
category_counts = {}
for feat, count in feature_counts.items():
    cat = feature_categories[feat]
    category_counts[cat] = category_counts.get(cat, 0) + count

for cat, count in sorted(category_counts.items(), key=lambda x: x[1], reverse=True):
    pct = (count / feature_counts.sum()) * 100
    print(f"  {cat:<15}: {count:>2} users ({pct:>5.1f}%)")

print(f"\nTop 3 Most Valued Features:")
for i, (feat, count) in enumerate(feature_counts.head(3).items()):
    pct = (count / feature_counts.sum()) * 100
    print(f"  {i+1}. {feat}: {count} users ({pct:.1f}%)")

print(f"\nFiles saved:")
print(f"  - CHART_C_Feature_Preferences.png (300 DPI)")
print(f"  - CHART_C_Feature_Preferences.pdf (300 DPI)")

plt.show()
