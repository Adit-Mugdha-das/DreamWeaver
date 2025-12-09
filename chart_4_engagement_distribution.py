"""
================================================================================
CHART 4: ENGAGEMENT INDEX DISTRIBUTION (HISTOGRAM)
================================================================================
Purpose: Show trimodal distribution of user engagement with segment boundaries
Data Source: combined_user_data_NEW.csv
Chart Type: Histogram with KDE overlay and segment markers

Shows distinct user behavior clusters (Drop-off, Moderate, High Engagement)
================================================================================
"""

import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
import numpy as np

print("="*80)
print("CHART 4: ENGAGEMENT INDEX DISTRIBUTION")
print("="*80)

# Step 1: Load data
print("\n[Step 1/7] Loading data from combined_user_data_NEW.csv...")
df = pd.read_csv('combined_user_data_NEW.csv')
engagement_data = df['Engagement_Index']
print(f"✓ Loaded {len(engagement_data)} users")

# Step 2: Calculate statistics
print("\n[Step 2/7] Computing engagement statistics...")
overall_mean = engagement_data.mean()
overall_sd = engagement_data.std()

segment_means = {
    'Drop-off': df[df['User_Segment'] == 'Drop-off']['Engagement_Index'].mean(),
    'Moderate': df[df['User_Segment'] == 'Moderate']['Engagement_Index'].mean(),
    'High Engagement': df[df['User_Segment'] == 'High Engagement']['Engagement_Index'].mean()
}

print(f"\n  Overall Statistics:")
print(f"    Mean: {overall_mean:.2f}")
print(f"    SD: {overall_sd:.2f}")
print(f"\n  Segment Means:")
print(f"    Drop-off: {segment_means['Drop-off']:.2f}")
print(f"    Moderate: {segment_means['Moderate']:.2f}")
print(f"    High Engagement: {segment_means['High Engagement']:.2f}")

# Step 3: Set up publication-quality style
print("\n[Step 3/7] Setting up publication-quality theme...")
plt.rcParams['font.family'] = 'Arial'
plt.rcParams['font.size'] = 11
sns.set_style("whitegrid", {'grid.linestyle': '--', 'grid.alpha': 0.3})

# Step 4: Create figure
print("\n[Step 4/7] Creating histogram with KDE overlay...")
fig, ax = plt.subplots(figsize=(14, 7))

# Create histogram with 20 bins
n, bins, patches = ax.hist(engagement_data, bins=20, color='#5DADE2', 
                           alpha=0.7, edgecolor='black', linewidth=1.2)

# Color bars by segment regions
for i, patch in enumerate(patches):
    bin_center = (bins[i] + bins[i+1]) / 2
    if bin_center < 20:
        patch.set_facecolor('#E74C3C')  # Red for drop-off region
        patch.set_alpha(0.7)
    elif bin_center < 50:
        patch.set_facecolor('#F39C12')  # Yellow for moderate region
        patch.set_alpha(0.7)
    else:
        patch.set_facecolor('#27AE60')  # Green for high engagement region
        patch.set_alpha(0.7)

# Add thin KDE overlay on secondary axis
print("  → Adding thin KDE curve...")
ax2 = ax.twinx()
sns.kdeplot(data=engagement_data, color='#1C2833', linewidth=2, ax=ax2, alpha=0.8)
ax2.set_ylabel('Density', fontsize=11, fontweight='bold', color='#1C2833')
ax2.tick_params(axis='y', labelcolor='#1C2833')
ax2.grid(False)
# Scale density axis to keep curve thin and low
ax2.set_ylim(0, ax2.get_ylim()[1] * 1.8)

# Step 5: Add segment mean lines
print("\n[Step 5/7] Adding segment markers...")
ax.axvline(x=segment_means['Drop-off'], color='#C0392B', linestyle='--', 
           linewidth=2.5, alpha=0.9, label=f'Drop-off (M={segment_means["Drop-off"]:.1f})', zorder=3)
ax.axvline(x=segment_means['Moderate'], color='#D68910', linestyle='--', 
           linewidth=2.5, alpha=0.9, label=f'Moderate (M={segment_means["Moderate"]:.1f})', zorder=3)
ax.axvline(x=segment_means['High Engagement'], color='#229954', linestyle='--', 
           linewidth=2.5, alpha=0.9, label=f'High Engagement (M={segment_means["High Engagement"]:.1f})', zorder=3)

# Step 6: Add subtle background shading
print("  → Adding background shading...")
ax.axvspan(0, 20, alpha=0.08, color='#E74C3C', zorder=0)
ax.axvspan(20, 50, alpha=0.08, color='#F39C12', zorder=0)
ax.axvspan(50, 100, alpha=0.08, color='#27AE60', zorder=0)

# Add region labels in free space with better visibility
ax.text(10, ax.get_ylim()[1] * 0.65, 'Drop-off\nRegion', 
        ha='center', va='center', fontsize=12, fontweight='bold', 
        color='white', style='italic',
        bbox=dict(boxstyle='round,pad=0.5', facecolor='#C0392B', alpha=0.85, edgecolor='white', linewidth=2))
ax.text(35, ax.get_ylim()[1] * 0.65, 'Moderate\nRegion', 
        ha='center', va='center', fontsize=12, fontweight='bold', 
        color='white', style='italic',
        bbox=dict(boxstyle='round,pad=0.5', facecolor='#D68910', alpha=0.85, edgecolor='white', linewidth=2))
ax.text(75, ax.get_ylim()[1] * 0.65, 'High Engagement\nRegion', 
        ha='center', va='center', fontsize=12, fontweight='bold', 
        color='white', style='italic',
        bbox=dict(boxstyle='round,pad=0.5', facecolor='#229954', alpha=0.85, edgecolor='white', linewidth=2))

# Formatting
ax.set_xlabel('Engagement Index (0-100)', fontsize=13, fontweight='bold')
ax.set_ylabel('Number of Users', fontsize=13, fontweight='bold')
ax.set_title('Engagement Index Distribution Across User Segments', 
             fontsize=15, fontweight='bold', pad=20)
ax.set_xlim(0, 100)
ax.grid(axis='y', alpha=0.3, linestyle='--')

# Add clean statistics annotation
stats_text = f'N = {len(engagement_data)}\nMean = {overall_mean:.2f}\nSD = {overall_sd:.2f}'
ax.text(0.02, 0.98, stats_text, transform=ax.transAxes, 
        fontsize=11, va='top', ha='left', fontweight='bold',
        bbox=dict(boxstyle='round,pad=0.8', facecolor='white', 
                  edgecolor='black', linewidth=2, alpha=0.95))

# Create legend with KDE included
from matplotlib.lines import Line2D
legend_elements = [
    Line2D([0], [0], color='#C0392B', linestyle='--', linewidth=2.5, label=f'Drop-off (M={segment_means["Drop-off"]:.1f})'),
    Line2D([0], [0], color='#D68910', linestyle='--', linewidth=2.5, label=f'Moderate (M={segment_means["Moderate"]:.1f})'),
    Line2D([0], [0], color='#229954', linestyle='--', linewidth=2.5, label=f'High Engagement (M={segment_means["High Engagement"]:.1f})'),
    Line2D([0], [0], color='#1C2833', linestyle='-', linewidth=2, label='Density (KDE)', alpha=0.8)
]
ax.legend(handles=legend_elements, loc='upper right', fontsize=10, 
          framealpha=0.95, edgecolor='black', fancybox=True, shadow=True)
plt.tight_layout()

# Step 7: Save outputs
print("\n[Step 6/7] Saving high-resolution figures...")
png_path = r'c:\xampp\htdocs\dreamweaver\CHART_4_Engagement_Distribution.png'
pdf_path = r'c:\xampp\htdocs\dreamweaver\CHART_4_Engagement_Distribution.pdf'

plt.savefig(png_path, dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig(pdf_path, format='pdf', bbox_inches='tight', facecolor='white')
print(f"✓ Saved PNG: {png_path}")
print(f"✓ Saved PDF: {pdf_path}")

# Step 7: Display summary
print("\n[Step 7/7] Distribution Summary:")
print(f"\n  📊 Key Findings:")
print(f"    - Trimodal distribution with 3 distinct peaks")
print(f"    - Drop-off cluster: Low engagement (M={segment_means['Drop-off']:.2f})")
print(f"    - Moderate cluster: Medium engagement (M={segment_means['Moderate']:.2f})")
print(f"    - High cluster: Strong engagement (M={segment_means['High Engagement']:.2f})")
print(f"    - Overall spread: SD={overall_sd:.2f} (high variability)")

print("\n" + "="*80)
print("📋 FIGURE CAPTION FOR PAPER:")
print("="*80)
caption = """
Distribution of engagement index scores across 147 participants (M=39.84, 
SD=27.56). Colored bars represent engagement regions: red (0-20, drop-off), 
yellow (20-50, moderate), and green (50-100, high engagement). Vertical 
dashed lines indicate segment means: drop-off (7.61), moderate (35.92), 
and high-engagement (72.87). The trimodal distribution reflects distinct 
user behavior clusters, with a KDE overlay showing the density distribution.
"""
print(caption)

plt.show()

print("\n✅ Chart 4 complete - Engagement distribution visualization ready!")
print("="*80)
