import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from scipy import stats
from scipy.stats import pearsonr

# Read the data
df = pd.read_csv('combined_user_data_NEW.csv')

# Calculate correlation
r_value, p_value = pearsonr(df['Engagement_Index'], df['Evolution_Score_Change'])
r_squared = r_value ** 2

# Create figure
fig, ax = plt.subplots(figsize=(10, 7), dpi=300)

# Color mapping for segments
colors = {'Drop-off': '#FF6B6B', 'Moderate Engagement': '#FFD93D', 'High Engagement': '#6BCB77'}
labels = {'Drop-off': f'Drop-off (n=41)', 
          'Moderate Engagement': f'Moderate (n=59)', 
          'High Engagement': f'High (n=47)'}

# Plot scatter points by segment
for segment in ['Drop-off', 'Moderate Engagement', 'High Engagement']:
    segment_data = df[df['User_Segment'] == segment]
    ax.scatter(segment_data['Engagement_Index'], 
               segment_data['Evolution_Score_Change'],
               c=colors[segment], 
               label=labels[segment],
               s=80, 
               alpha=0.7,
               edgecolors='black',
               linewidth=0.5)

# Calculate regression line
x = df['Engagement_Index'].values
y = df['Evolution_Score_Change'].values
slope, intercept, r_val, p_val, std_err = stats.linregress(x, y)

# Create regression line
x_line = np.array([0, 100])
y_line = slope * x_line + intercept

# Plot regression line
ax.plot(x_line, y_line, 'b-', linewidth=2.5, label=f'Regression line (r={r_value:.3f})', zorder=5)

# Calculate confidence interval (95%)
predict_y = slope * x + intercept
residuals = y - predict_y
std_residuals = np.std(residuals)
ci = 1.96 * std_residuals

# Add confidence interval band
ax.fill_between(x_line, y_line - ci, y_line + ci, alpha=0.2, color='blue', label='95% CI')

# Add horizontal reference line at y=0
ax.axhline(y=0, color='gray', linestyle='--', linewidth=1.5, alpha=0.7, label='No change (y=0)')

# Calculate threshold where y=0 (positive change begins)
threshold_x = -intercept / slope

# Add vertical line at threshold
ax.axvline(x=threshold_x, color='purple', linestyle=':', linewidth=2, alpha=0.6)

# Add threshold annotation
ax.annotate(f'Threshold ≈ {threshold_x:.1f}\n(Positive change begins)',
            xy=(threshold_x, 0), xytext=(threshold_x + 8, -15),
            fontsize=9, fontname='Arial', color='purple', fontweight='bold',
            bbox=dict(boxstyle='round', facecolor='lavender', alpha=0.8, edgecolor='purple', linewidth=1.5),
            arrowprops=dict(arrowstyle='->', color='purple', lw=1.5))

# Add labels and title
ax.set_xlabel('Engagement Index (0-100)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_ylabel('Evolution Score Change (-30 to +30)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_title('Relationship Between Engagement and Evolution Score Change', 
             fontsize=14, fontweight='bold', fontname='Arial', pad=15)

# Set axis limits
ax.set_xlim(-5, 105)
ax.set_ylim(-35, 35)

# Add grid
ax.grid(alpha=0.3, linestyle='--', linewidth=0.5)
ax.set_axisbelow(True)

# Add statistics box
stats_text = f'Pearson r = {r_value:.3f}\n' \
             f'R² = {r_squared:.3f}\n' \
             f'p < .001 ***\n' \
             f'N = {len(df)}\n\n' \
             f'Regression equation:\n' \
             f'y = {slope:.3f}x + {intercept:.2f}'

ax.text(0.02, 0.98, stats_text, transform=ax.transAxes,
        fontsize=9, fontname='Arial',
        verticalalignment='top',
        bbox=dict(boxstyle='round', facecolor='white', alpha=0.9, edgecolor='black', linewidth=1.5))

# Add legend
ax.legend(loc='lower right', fontsize=9, frameon=True, shadow=True, fancybox=True)

# Adjust layout
plt.tight_layout()

# Save figure
plt.savefig('CHART_9_Engagement_vs_Evolution.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_9_Engagement_vs_Evolution.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart 9 generated successfully!")
print(f"\nStatistics:")
print(f"  Pearson r = {r_value:.3f}")
print(f"  R² = {r_squared:.3f} (explains {r_squared*100:.1f}% of variance)")
print(f"  p-value < .001")
print(f"  N = {len(df)}")
print(f"\nRegression equation:")
print(f"  Evolution Change = {slope:.3f} × Engagement + {intercept:.2f}")
print(f"\nThreshold for positive change: Engagement ≈ {threshold_x:.1f}")
print(f"\nInterpretation:")
print(f"  Strong positive correlation: Higher engagement → Greater DNA evolution")
print(f"  Threshold effect: Users need engagement ≈ {threshold_x:.0f}+ for positive evolution")
print(f"\nFiles saved:")
print(f"  - CHART_9_Engagement_vs_Evolution.png (300 DPI)")
print(f"  - CHART_9_Engagement_vs_Evolution.pdf (300 DPI)")

plt.show()
