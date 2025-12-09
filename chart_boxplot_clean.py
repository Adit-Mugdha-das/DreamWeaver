import pandas as pd
import matplotlib.pyplot as plt
import numpy as np

# Load data
df = pd.read_csv('combined_user_data_NEW.csv')

# Prepare data for boxplots
segments_display = ['Drop-off', 'Moderate', 'High Engagement']
segments_data = ['Drop-off', 'Moderate', 'High Engagement']

# Create figure with two subplots
fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(14, 7), dpi=300)

# Prepare data for each segment
evolution_data = [df[df['User_Segment'] == seg]['Evolution_Score_Change'].values for seg in segments_data]
health_data = [df[df['User_Segment'] == seg]['Health_Score_Change'].values for seg in segments_data]

# Define colors
colors = ['#E74C3C', '#F39C12', '#2ECC71']  # Red, Orange, Green

# ========== Panel 1: Evolution Score Change ==========
bp1 = ax1.boxplot(evolution_data, 
                   positions=[1, 2, 3],
                   widths=0.6,
                   patch_artist=True,
                   showmeans=True,
                   meanline=False,
                   notch=False,
                   meanprops=dict(marker='D', markerfacecolor='white', markeredgecolor='black', markersize=8),
                   medianprops=dict(color='black', linewidth=2.5),
                   boxprops=dict(linewidth=2, edgecolor='black'),
                   whiskerprops=dict(linewidth=2, color='black'),
                   capprops=dict(linewidth=2, color='black'),
                   flierprops=dict(marker='o', markerfacecolor='gray', markersize=6, alpha=0.6, markeredgecolor='black'))

# Color the boxes
for patch, color in zip(bp1['boxes'], colors):
    patch.set_facecolor(color)
    patch.set_alpha(0.7)

# Customize Panel 1
ax1.set_ylabel('Evolution Score Change', fontsize=12, fontweight='bold', fontname='Arial')
ax1.set_xlabel('User Segment', fontsize=12, fontweight='bold', fontname='Arial')
ax1.set_title('Evolution Score Change by Engagement Level', fontsize=13, fontweight='bold', fontname='Arial', pad=15)
ax1.set_xticks([1, 2, 3])
ax1.set_xticklabels([f"{seg}\n(n={len(data)})" for seg, data in zip(segments_display, evolution_data)], 
                     fontsize=11, fontname='Arial')
ax1.axhline(y=0, color='gray', linestyle='--', linewidth=1, alpha=0.5)
ax1.grid(axis='y', alpha=0.3, linestyle='--', linewidth=0.5)
ax1.set_axisbelow(True)
ax1.set_ylim(-50, 50)

# Add mean values on top of boxes
for i, (data, color) in enumerate(zip(evolution_data, colors)):
    if len(data) > 0:  # Check if data exists
        mean_val = np.mean(data)
        max_val = np.max(data)
        ax1.text(i+1, max_val + 3, f'M={mean_val:.1f}', 
                ha='center', va='bottom', fontsize=9, fontweight='bold', fontname='Arial')

# ========== Panel 2: Health Score Change ==========
bp2 = ax2.boxplot(health_data, 
                   positions=[1, 2, 3],
                   widths=0.6,
                   patch_artist=True,
                   showmeans=True,
                   meanline=False,
                   notch=False,
                   meanprops=dict(marker='D', markerfacecolor='white', markeredgecolor='black', markersize=8),
                   medianprops=dict(color='black', linewidth=2.5),
                   boxprops=dict(linewidth=2, edgecolor='black'),
                   whiskerprops=dict(linewidth=2, color='black'),
                   capprops=dict(linewidth=2, color='black'),
                   flierprops=dict(marker='o', markerfacecolor='gray', markersize=6, alpha=0.6, markeredgecolor='black'))

# Color the boxes
for patch, color in zip(bp2['boxes'], colors):
    patch.set_facecolor(color)
    patch.set_alpha(0.7)

# Customize Panel 2
ax2.set_ylabel('Health Score Change', fontsize=12, fontweight='bold', fontname='Arial')
ax2.set_xlabel('User Segment', fontsize=12, fontweight='bold', fontname='Arial')
ax2.set_title('Health Score Change by Engagement Level', fontsize=13, fontweight='bold', fontname='Arial', pad=15)
ax2.set_xticks([1, 2, 3])
ax2.set_xticklabels([f"{seg}\n(n={len(data)})" for seg, data in zip(segments_display, health_data)], 
                     fontsize=11, fontname='Arial')
ax2.axhline(y=0, color='gray', linestyle='--', linewidth=1, alpha=0.5)
ax2.grid(axis='y', alpha=0.3, linestyle='--', linewidth=0.5)
ax2.set_axisbelow(True)
ax2.set_ylim(-50, 50)

# Add mean values on top of boxes
for i, (data, color) in enumerate(zip(health_data, colors)):
    if len(data) > 0:  # Check if data exists
        mean_val = np.mean(data)
        max_val = np.max(data)
        ax2.text(i+1, max_val + 3, f'M={mean_val:.1f}', 
                ha='center', va='bottom', fontsize=9, fontweight='bold', fontname='Arial')

# Add main title
fig.suptitle('Score Changes Across User Engagement Segments (N=147)', 
             fontsize=14, fontweight='bold', fontname='Arial', y=0.98)

# Add legend
from matplotlib.patches import Patch
from matplotlib.lines import Line2D
legend_elements = [
    Patch(facecolor='#E74C3C', alpha=0.7, edgecolor='black', label='Drop-off'),
    Patch(facecolor='#F39C12', alpha=0.7, edgecolor='black', label='Moderate Engagement'),
    Patch(facecolor='#2ECC71', alpha=0.7, edgecolor='black', label='High Engagement'),
    Line2D([0], [0], color='black', linewidth=2, label='Median'),
    Line2D([0], [0], marker='D', color='w', markerfacecolor='white', 
           markeredgecolor='black', markersize=8, label='Mean', linestyle='None')
]
fig.legend(handles=legend_elements, loc='lower center', ncol=5, 
          fontsize=10, frameon=True, bbox_to_anchor=(0.5, -0.02))

# Adjust layout
plt.tight_layout(rect=[0, 0.03, 1, 0.96])

# Save
plt.savefig('CHART_3_Boxplots_Clean.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_3_Boxplots_Clean.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Clean boxplot chart generated successfully!")
print("\nStatistics Summary:")
print("="*70)
print(f"{'Segment':<20} {'Evolution M±SD':<20} {'Health M±SD':<20}")
print("-"*70)
for seg, evo_data, health_data in zip(segments_display, evolution_data, health_data):
    evo_m = np.mean(evo_data)
    evo_sd = np.std(evo_data, ddof=1)
    health_m = np.mean(health_data)
    health_sd = np.std(health_data, ddof=1)
    print(f"{seg:<20} {evo_m:>6.2f}±{evo_sd:<5.2f}      {health_m:>6.2f}±{health_sd:<5.2f}")

print("\nBoxplot Components:")
print("  • Box: 25th to 75th percentile (IQR)")
print("  • Black line in box: Median")
print("  • White diamond: Mean")
print("  • Whiskers: Extend to 1.5 × IQR")
print("  • Gray dots: Outliers beyond whiskers")
print("  • Dashed line at 0: No change reference")

print("\nFiles saved:")
print("  - CHART_3_Boxplots_Clean.png (300 DPI)")
print("  - CHART_3_Boxplots_Clean.pdf (300 DPI)")

plt.show()
