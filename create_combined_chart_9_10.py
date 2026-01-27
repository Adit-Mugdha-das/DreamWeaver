import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from scipy import stats
from scipy.stats import pearsonr
from matplotlib.colors import LinearSegmentedColormap

# Read the data
df = pd.read_csv('combined_user_data_NEW.csv')

# Create figure with two subplots side by side
fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(18, 7), dpi=300)
fig.suptitle('Engagement Predicts Psychological Outcomes in Dose-Response Fashion', 
             fontsize=16, fontweight='bold', fontname='Arial', y=0.98)

# ============================================================================
# PANEL A: Engagement Index × Evolution Score Change
# ============================================================================

# Calculate correlation for panel A
r_value_A, p_value_A = pearsonr(df['Engagement_Index'], df['Evolution_Score_Change'])
r_squared_A = r_value_A ** 2

# Color mapping for segments
colors = {'Drop-off': '#FF6B6B', 'Moderate Engagement': '#FFD93D', 'High Engagement': '#6BCB77'}
labels = {'Drop-off': f'Drop-off (n=41)', 
          'Moderate Engagement': f'Moderate (n=59)', 
          'High Engagement': f'High (n=47)'}

# Plot scatter points by segment
for segment in ['Drop-off', 'Moderate Engagement', 'High Engagement']:
    segment_data = df[df['User_Segment'] == segment]
    ax1.scatter(segment_data['Engagement_Index'], 
               segment_data['Evolution_Score_Change'],
               c=colors[segment], 
               label=labels[segment],
               s=60, 
               alpha=0.7,
               edgecolors='black',
               linewidth=0.5)

# Calculate regression line for panel A
x_A = df['Engagement_Index'].values
y_A = df['Evolution_Score_Change'].values
slope_A, intercept_A, r_val_A, p_val_A, std_err_A = stats.linregress(x_A, y_A)

# Create regression line
x_line_A = np.array([0, 100])
y_line_A = slope_A * x_line_A + intercept_A

# Plot regression line
ax1.plot(x_line_A, y_line_A, 'b-', linewidth=2.5, label=f'Regression line (r={r_value_A:.3f})', zorder=5)

# Calculate confidence interval (95%)
predict_y_A = slope_A * x_A + intercept_A
residuals_A = y_A - predict_y_A
std_residuals_A = np.std(residuals_A)
ci_A = 1.96 * std_residuals_A

# Add confidence interval band
ax1.fill_between(x_line_A, y_line_A - ci_A, y_line_A + ci_A, alpha=0.2, color='blue', label='95% CI')

# Add horizontal reference line at y=0
ax1.axhline(y=0, color='gray', linestyle='--', linewidth=1.5, alpha=0.7, label='No change (y=0)')

# Calculate threshold where y=0
threshold_x_A = -intercept_A / slope_A

# Add vertical line at threshold
ax1.axvline(x=threshold_x_A, color='purple', linestyle=':', linewidth=2, alpha=0.6)

# Add threshold annotation
ax1.annotate(f'Threshold ≈ {threshold_x_A:.1f}',
            xy=(threshold_x_A, 0), xytext=(threshold_x_A + 8, -15),
            fontsize=8, fontname='Arial', color='purple', fontweight='bold',
            bbox=dict(boxstyle='round', facecolor='lavender', alpha=0.8, edgecolor='purple', linewidth=1.5),
            arrowprops=dict(arrowstyle='->', color='purple', lw=1.5))

# Panel A labels
ax1.set_xlabel('Engagement Index (0-100)', fontsize=11, fontweight='bold', fontname='Arial')
ax1.set_ylabel('Evolution Score Change', fontsize=11, fontweight='bold', fontname='Arial')
ax1.set_title('(A) Engagement Index → Evolution Score', 
             fontsize=13, fontweight='bold', fontname='Arial', pad=10)

# Set axis limits
ax1.set_xlim(-5, 105)
ax1.set_ylim(-35, 35)

# Add grid
ax1.grid(alpha=0.3, linestyle='--', linewidth=0.5)
ax1.set_axisbelow(True)

# Add statistics box
stats_text_A = f'r = {r_value_A:.3f}\n' \
             f'R² = {r_squared_A:.3f}\n' \
             f'p < .001\n' \
             f'N = {len(df)}'

ax1.text(0.02, 0.98, stats_text_A, transform=ax1.transAxes,
        fontsize=9, fontname='Arial',
        verticalalignment='top',
        bbox=dict(boxstyle='round', facecolor='white', alpha=0.9, edgecolor='black', linewidth=1.5))

# Add legend
ax1.legend(loc='lower right', fontsize=8, frameon=True, shadow=True, fancybox=True)

# ============================================================================
# PANEL B: Features Used × Health Score Change
# ============================================================================

# Calculate correlation for panel B
r_value_B, p_value_B = pearsonr(df['Features_Used_Count'], df['Health_Score_Change'])
r_squared_B = r_value_B ** 2

# Create color gradient based on engagement level
engagement_normalized = (df['Engagement_Index'] - df['Engagement_Index'].min()) / \
                        (df['Engagement_Index'].max() - df['Engagement_Index'].min())

# Create custom colormap
colors_list = ['#9D84B7', '#E8A0BF', '#FF9A8B', '#FFD966']
n_bins = 100
cmap = LinearSegmentedColormap.from_list('engagement', colors_list, N=n_bins)

# Plot scatter points with color gradient
scatter = ax2.scatter(df['Features_Used_Count'], 
                     df['Health_Score_Change'],
                     c=df['Engagement_Index'],
                     cmap=cmap,
                     s=60, 
                     alpha=0.7,
                     edgecolors='black',
                     linewidth=0.5,
                     vmin=0,
                     vmax=100)

# Add colorbar
cbar = plt.colorbar(scatter, ax=ax2, pad=0.02)
cbar.set_label('Engagement Index', fontsize=10, fontweight='bold', fontname='Arial')
cbar.ax.tick_params(labelsize=8)

# Calculate regression line for panel B
x_B = df['Features_Used_Count'].values
y_B = df['Health_Score_Change'].values
slope_B, intercept_B, r_val_B, p_val_B, std_err_B = stats.linregress(x_B, y_B)

# Create regression line
x_line_B = np.array([0, 18])
y_line_B = slope_B * x_line_B + intercept_B

# Plot regression line
ax2.plot(x_line_B, y_line_B, 'b-', linewidth=2.5, label=f'Regression line (r={r_value_B:.3f})', zorder=5)

# Calculate confidence interval (95%)
predict_y_B = slope_B * x_B + intercept_B
residuals_B = y_B - predict_y_B
std_residuals_B = np.std(residuals_B)
ci_B = 1.96 * std_residuals_B

# Add confidence interval band
ax2.fill_between(x_line_B, y_line_B - ci_B, y_line_B + ci_B, alpha=0.2, color='blue', label='95% CI')

# Add horizontal reference line at y=0
ax2.axhline(y=0, color='gray', linestyle='--', linewidth=1.5, alpha=0.7, label='No change (y=0)')

# Calculate threshold where y=0
threshold_x_B = -intercept_B / slope_B

# Add vertical line at threshold
ax2.axvline(x=threshold_x_B, color='purple', linestyle=':', linewidth=2, alpha=0.6)

# Add threshold annotation
ax2.annotate(f'Threshold ≈ {threshold_x_B:.1f} features',
            xy=(threshold_x_B, 0), xytext=(threshold_x_B + 1.5, -15),
            fontsize=8, fontname='Arial', color='purple', fontweight='bold',
            bbox=dict(boxstyle='round', facecolor='lavender', alpha=0.8, edgecolor='purple', linewidth=1.5),
            arrowprops=dict(arrowstyle='->', color='purple', lw=1.5))

# Panel B labels
ax2.set_xlabel('Features Used (0-18)', fontsize=11, fontweight='bold', fontname='Arial')
ax2.set_ylabel('Health Score Change', fontsize=11, fontweight='bold', fontname='Arial')
ax2.set_title('(B) Feature Diversity → Health Score', 
             fontsize=13, fontweight='bold', fontname='Arial', pad=10)

# Set axis limits
ax2.set_xlim(-0.5, 18.5)
ax2.set_ylim(-35, 35)

# Set x-axis ticks
ax2.set_xticks(np.arange(0, 19, 2))

# Add grid
ax2.grid(alpha=0.3, linestyle='--', linewidth=0.5)
ax2.set_axisbelow(True)

# Add statistics box
stats_text_B = f'r = {r_value_B:.3f}\n' \
             f'R² = {r_squared_B:.3f}\n' \
             f'p < .001\n' \
             f'N = {len(df)}'

ax2.text(0.02, 0.98, stats_text_B, transform=ax2.transAxes,
        fontsize=9, fontname='Arial',
        verticalalignment='top',
        bbox=dict(boxstyle='round', facecolor='white', alpha=0.9, edgecolor='black', linewidth=1.5))

# Add legend
ax2.legend(loc='lower right', fontsize=8, frameon=True, shadow=True, fancybox=True)

# Adjust layout
plt.tight_layout(rect=[0, 0, 1, 0.96])

# Save the figure
plt.savefig('CHART_9_10_COMBINED.pdf', dpi=300, bbox_inches='tight', format='pdf')
print("✓ Combined chart saved as CHART_9_10_COMBINED.pdf")

# Also save as PNG for preview
plt.savefig('CHART_9_10_COMBINED.png', dpi=300, bbox_inches='tight')
print("✓ Preview saved as CHART_9_10_COMBINED.png")

plt.show()

# Print correlation values to verify they match the paper
print("\n=== Verification ===")
print(f"Panel A - Engagement × Evolution: r={r_value_A:.3f}, R²={r_squared_A:.3f}")
print(f"Panel B - Features × Health: r={r_value_B:.3f}, R²={r_squared_B:.3f}")
print(f"Threshold Panel A (Engagement): {threshold_x_A:.1f}")
print(f"Threshold Panel B (Features): {threshold_x_B:.1f}")
print("\nThese values should match the paper:")
print("Expected: r=.884 (Panel A), r=.932 (Panel B)")
print("Expected: R²=.781 (Panel A), R²=.869 (Panel B)")
print("Expected: Threshold=28.8 (Panel A), Threshold≈7 (Panel B)")
