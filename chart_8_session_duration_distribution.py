import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from scipy import stats

# Read the data
df = pd.read_csv('combined_user_data_NEW.csv')

# Calculate session duration statistics
avg_session = df['Avg_Session_Minutes']
mean_val = avg_session.mean()
median_val = avg_session.median()
std_val = avg_session.std()
min_val = avg_session.min()
max_val = avg_session.max()
skewness = stats.skew(avg_session)
n = len(avg_session)

# Create figure
fig, ax = plt.subplots(figsize=(10, 6), dpi=300)

# Create histogram with beautiful color gradient
# Define bins
bins = np.arange(0, 75, 5)  # 0-5, 5-10, ..., 70-75
n_vals, bins_edges, patches = ax.hist(avg_session, bins=bins, edgecolor='black', linewidth=0.8, alpha=0.85)

# Color gradient: Light coral (short sessions) -> Orange -> Gold -> Light green (long sessions)
colors = ['#FFB6C1', '#FFA07A', '#FF8C69', '#FFD700', '#F0E68C', '#E6E68A', 
          '#98FB98', '#90EE90', '#7CCD7C', '#6AB06A', '#59A059', '#4B8B4B',
          '#3D7D3D', '#2F6F2F']
for i, patch in enumerate(patches):
    if i < len(colors):
        patch.set_facecolor(colors[i])
    else:
        patch.set_facecolor(colors[-1])

# Add mean line (dashed)
ax.axvline(mean_val, color='darkred', linestyle='--', linewidth=2.5, 
           label=f'Mean = {mean_val:.2f} min', zorder=10)

# Add median line (dotted)
ax.axvline(median_val, color='navy', linestyle=':', linewidth=2.5, 
           label=f'Median = {median_val:.2f} min', zorder=10)

# Add labels and title
ax.set_xlabel('Average Session Duration (minutes)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_ylabel('Frequency (Number of Users)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_title('Distribution of Average Session Duration', fontsize=14, fontweight='bold', fontname='Arial', pad=15)

# Set x-axis limits and ticks
ax.set_xlim(0, 75)
ax.set_xticks(np.arange(0, 80, 10))

# Add grid for readability
ax.grid(axis='y', alpha=0.3, linestyle='--', linewidth=0.5)
ax.set_axisbelow(True)

# Add statistics box (positioned to avoid overlapping with legend)
stats_text = f'N = {n}\n' \
             f'Mean = {mean_val:.2f} min\n' \
             f'Median = {median_val:.2f} min\n' \
             f'SD = {std_val:.2f}\n' \
             f'Range = {min_val:.1f} - {max_val:.1f}\n' \
             f'Skewness = {skewness:.2f}'

# Position at upper area, slightly left (x=0.16, y=0.98) to avoid bar overlap
ax.text(0.16, 0.98, stats_text, transform=ax.transAxes,
        fontsize=10, fontname='Arial',
        verticalalignment='top',
        bbox=dict(boxstyle='round', facecolor='wheat', alpha=0.8, edgecolor='black', linewidth=1.5))

# Add skewness interpretation annotation on the right side, moved up
skew_interpretation = 'Right-skewed:\nMost users have brief sessions,\npower users engage 50+ min'
ax.text(0.99, 0.62, skew_interpretation, transform=ax.transAxes,
        fontsize=9, fontname='Arial', style='italic',
        verticalalignment='top', horizontalalignment='right',
        bbox=dict(boxstyle='round', facecolor='lightyellow', alpha=0.7, edgecolor='gray', linewidth=1))

# Add legend
ax.legend(loc='upper right', fontsize=10, frameon=True, shadow=True, fancybox=True)

# Adjust layout
plt.tight_layout()

# Save figure
plt.savefig('CHART_8_Session_Duration_Distribution.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_8_Session_Duration_Distribution.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart 8 generated successfully!")
print(f"\nStatistics:")
print(f"  N = {n}")
print(f"  Mean = {mean_val:.2f} minutes")
print(f"  Median = {median_val:.2f} minutes")
print(f"  SD = {std_val:.2f}")
print(f"  Range = {min_val:.1f} - {max_val:.1f}")
print(f"  Skewness = {skewness:.2f}")
print(f"\nInterpretation: {'Right-skewed distribution' if skewness > 0.5 else 'Approximately normal distribution'}")
print(f"  Most users: {median_val:.0f}-minute sessions")
print(f"  Power users: 50+ minute sessions")
print(f"\nFiles saved:")
print(f"  - CHART_8_Session_Duration_Distribution.png (300 DPI)")
print(f"  - CHART_8_Session_Duration_Distribution.pdf (300 DPI)")

plt.show()
