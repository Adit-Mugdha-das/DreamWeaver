import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

# Read the data
df = pd.read_csv('combined_user_data_NEW.csv')

# Get emotion distribution
emotion_counts = df['Dominant_Emotion'].value_counts()
emotion_percentages = df['Dominant_Emotion'].value_counts(normalize=True) * 100

# Remove '-' (no emotion) for cleaner visualization
emotions_with_data = emotion_counts[emotion_counts.index != '-']
percentages_with_data = emotion_percentages[emotion_percentages.index != '-']

# Sort by count descending
emotions_sorted = emotions_with_data.sort_values(ascending=False)
percentages_sorted = percentages_with_data[emotions_sorted.index]

# Create figure
fig, ax = plt.subplots(figsize=(10, 7), dpi=300)

# Define beautiful colors for emotions
emotion_colors = {
    'Joy': '#FFD93D',      # Bright Yellow
    'Surprise': '#6BCB77',  # Green
    'Sadness': '#4D96FF',   # Blue
    'Fear': '#9D84B7',      # Purple
    'Anger': '#FF6B6B'      # Red
}

# Create stacked bar chart (horizontal for better readability)
colors = [emotion_colors.get(emotion, '#CCCCCC') for emotion in emotions_sorted.index]

# Single horizontal stacked bar
left = 0
bar_height = 0.6

for i, (emotion, percentage) in enumerate(zip(emotions_sorted.index, percentages_sorted)):
    count = emotions_sorted[emotion]
    ax.barh(0, percentage, left=left, height=bar_height, 
            label=f'{emotion} (n={count}, {percentage:.1f}%)',
            color=colors[i], edgecolor='black', linewidth=1.5)
    
    # Add percentage label in the middle of each segment
    # Show all labels, adjust positioning for small segments
    if percentage > 4:  # Normal centered label for larger segments
        ax.text(left + percentage/2, 0, f'{percentage:.1f}%',
                ha='center', va='center', fontsize=11, fontweight='bold',
                color='black', fontname='Arial')
    else:  # Small segment - show label outside to the right
        ax.text(left + percentage + 0.5, 0, f'{percentage:.1f}%',
                ha='left', va='center', fontsize=9, fontweight='bold',
                color='black', fontname='Arial')
    
    left += percentage

# Customize axes
ax.set_xlim(0, 100)
ax.set_ylim(-0.5, 0.5)
ax.set_xlabel('Percentage of Users (%)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_yticks([])
ax.set_title('Distribution of Dominant Emotions in Dreams\n(N=147 users)', 
             fontsize=14, fontweight='bold', fontname='Arial', pad=20)

# Add grid for x-axis
ax.xaxis.grid(True, linestyle='--', alpha=0.3, linewidth=0.5)
ax.set_axisbelow(True)

# Add legend
ax.legend(loc='upper center', bbox_to_anchor=(0.5, -0.08), fontsize=10, 
          frameon=True, shadow=True, fancybox=True, ncol=3,
          edgecolor='black', facecolor='white')

# Add statistics box
no_emotion_count = emotion_counts.get('-', 0)
total_with_emotion = len(df) - no_emotion_count

stats_text = f'Total Users: N = {len(df)}\n' \
             f'Users with emotions: {total_with_emotion} ({total_with_emotion/len(df)*100:.1f}%)\n' \
             f'Users without emotions: {no_emotion_count} ({no_emotion_count/len(df)*100:.1f}%)\n\n' \
             f'Most common: {emotions_sorted.index[0]} ({emotions_sorted.iloc[0]} users)\n' \
             f'Least common: {emotions_sorted.index[-1]} ({emotions_sorted.iloc[-1]} users)'

ax.text(0.02, 0.98, stats_text, transform=ax.transAxes,
        fontsize=9, fontname='Arial',
        verticalalignment='top',
        bbox=dict(boxstyle='round', facecolor='lightyellow', alpha=0.9, 
                 edgecolor='darkorange', linewidth=1.5))

# Add interpretation box
interpretation = 'Emotion Insights:\n\n' \
                 f'• Joy dominates (27.2%)\n' \
                 f'• Positive emotions prevalent\n' \
                 f'• Fear least common (13.6%)\n' \
                 f'• Balanced emotion diversity'

ax.text(0.98, 0.98, interpretation, transform=ax.transAxes,
        fontsize=9, fontname='Arial', style='italic',
        verticalalignment='top', horizontalalignment='right',
        bbox=dict(boxstyle='round', facecolor='lightblue', alpha=0.8, 
                 edgecolor='navy', linewidth=1.5))

# Adjust layout
plt.tight_layout()

# Save figure
plt.savefig('CHART_14_Emotion_Distribution.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_14_Emotion_Distribution.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart 14 generated successfully!")
print(f"\nEmotion Distribution (Stacked Bar):")
print(f"\nTotal Users: N = {len(df)}")
print(f"Users with dominant emotions: {total_with_emotion} ({total_with_emotion/len(df)*100:.1f}%)")
print(f"Users without emotions (-): {no_emotion_count} ({no_emotion_count/len(df)*100:.1f}%)")
print(f"\nEmotion Breakdown:")
for emotion, count in emotions_sorted.items():
    percentage = percentages_sorted[emotion]
    print(f"  {emotion}: n={count} ({percentage:.1f}%)")
print(f"\nKey Findings:")
print(f"  • Joy is the most common emotion (27.2% of users)")
print(f"  • Positive emotions (Joy, Surprise) represent 47.6% combined")
print(f"  • Emotional diversity across dream content")
print(f"\nFiles saved:")
print(f"  - CHART_14_Emotion_Distribution.png (300 DPI)")
print(f"  - CHART_14_Emotion_Distribution.pdf (300 DPI)")

plt.show()
