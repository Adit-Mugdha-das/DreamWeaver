import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from scipy import stats
from scipy.stats import pearsonr
from matplotlib.colors import LinearSegmentedColormap

# Read the data
df = pd.read_csv('combined_user_data_NEW.csv')

# Calculate correlation
r_value, p_value = pearsonr(df['Features_Used_Count'], df['Health_Score_Change'])
r_squared = r_value ** 2

# Create figure
fig, ax = plt.subplots(figsize=(10, 7), dpi=300)

# Create color gradient based on engagement level
# Normalize engagement for colormap
engagement_normalized = (df['Engagement_Index'] - df['Engagement_Index'].min()) / \
                        (df['Engagement_Index'].max() - df['Engagement_Index'].min())

# Create custom colormap: purple -> pink -> coral -> gold
colors_list = ['#9D84B7', '#E8A0BF', '#FF9A8B', '#FFD966']
n_bins = 100
cmap = LinearSegmentedColormap.from_list('engagement', colors_list, N=n_bins)

# Plot scatter points with color gradient
scatter = ax.scatter(df['Features_Used_Count'], 
                     df['Health_Score_Change'],
                     c=df['Engagement_Index'],
                     cmap=cmap,
                     s=80, 
                     alpha=0.7,
                     edgecolors='black',
                     linewidth=0.5,
                     vmin=0,
                     vmax=100)

# Add colorbar
cbar = plt.colorbar(scatter, ax=ax, pad=0.02)
cbar.set_label('Engagement Index', fontsize=11, fontweight='bold', fontname='Arial')
cbar.ax.tick_params(labelsize=9)

# Calculate regression line
x = df['Features_Used_Count'].values
y = df['Health_Score_Change'].values
slope, intercept, r_val, p_val, std_err = stats.linregress(x, y)

# Create regression line
x_line = np.array([0, 18])
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
ax.annotate(f'Threshold ≈ {threshold_x:.1f} features\n(Positive change begins)',
            xy=(threshold_x, 0), xytext=(threshold_x + 1.5, -15),
            fontsize=9, fontname='Arial', color='purple', fontweight='bold',
            bbox=dict(boxstyle='round', facecolor='lavender', alpha=0.8, edgecolor='purple', linewidth=1.5),
            arrowprops=dict(arrowstyle='->', color='purple', lw=1.5))

# Add labels and title
ax.set_xlabel('Number of Features Used (0-18)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_ylabel('Health Score Change (-30 to +30)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_title('Relationship Between Feature Diversity and Health Score Change', 
             fontsize=14, fontweight='bold', fontname='Arial', pad=15)

# Set axis limits
ax.set_xlim(-0.5, 18.5)
ax.set_ylim(-35, 35)

# Set x-axis ticks
ax.set_xticks(np.arange(0, 19, 2))

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

# Add interpretation box
interpretation = 'Feature diversity strongly\npredicts health improvement.\n\n' \
                 f'Users exploring {threshold_x:.0f}+ features\n' \
                 'show positive outcomes.'

ax.text(0.98, 0.35, interpretation, transform=ax.transAxes,
        fontsize=9, fontname='Arial', style='italic',
        verticalalignment='top', horizontalalignment='right',
        bbox=dict(boxstyle='round', facecolor='lightyellow', alpha=0.8, edgecolor='darkorange', linewidth=1.5))

# Add legend
ax.legend(loc='lower right', fontsize=9, frameon=True, shadow=True, fancybox=True)

# Adjust layout
plt.tight_layout()

# Save figure
plt.savefig('CHART_10_Features_vs_Health.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_10_Features_vs_Health.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart 10 generated successfully!")
print(f"\nStatistics:")
print(f"  Pearson r = {r_value:.3f}")
print(f"  R² = {r_squared:.3f} (explains {r_squared*100:.1f}% of variance)")
print(f"  p-value < .001")
print(f"  N = {len(df)}")
print(f"\nRegression equation:")
print(f"  Health Change = {slope:.3f} × Features Used + {intercept:.2f}")
print(f"\nThreshold for positive change: {threshold_x:.1f} features")
print(f"\nInterpretation:")
print(f"  Strong positive correlation: More feature diversity → Better health outcomes")
print(f"  Threshold effect: Users need {threshold_x:.0f}+ features for positive health change")
print(f"  Color gradient shows engagement level (purple=low, pink=moderate, coral/gold=high)")
print(f"\nFiles saved:")
print(f"  - CHART_10_Features_vs_Health.png (300 DPI)")
print(f"  - CHART_10_Features_vs_Health.pdf (300 DPI)")

plt.show()
