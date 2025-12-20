import matplotlib.pyplot as plt
import numpy as np

# Set publication-quality style
plt.style.use('seaborn-v0_8-paper')
plt.rcParams['font.family'] = 'serif'
plt.rcParams['font.size'] = 11
plt.rcParams['figure.dpi'] = 300

# Set random seed for reproducibility
np.random.seed(42)

# Generate synthetic data for three clusters based on the paper's segmentation
# Low engagement: n=41, Engagement Index < 20
low_x = np.random.uniform(0, 100, 41)  # Random behavioral metric
low_y = np.random.uniform(0, 20, 41)   # Engagement Index < 20

# Moderate engagement: n=59, Engagement Index 20-60
mod_x = np.random.uniform(0, 100, 59)
mod_y = np.random.uniform(20, 60, 59)

# High engagement: n=47, Engagement Index >= 60
high_x = np.random.uniform(0, 100, 47)
high_y = np.random.uniform(60, 100, 47)

# Create figure
fig, ax = plt.subplots(figsize=(9, 6))

# Plot scatter points for each cluster
scatter1 = ax.scatter(low_x, low_y, c='red', alpha=0.6, s=50, edgecolors='darkred', 
                      linewidth=0.5, label='Low (n=41, Index<20)')
scatter2 = ax.scatter(mod_x, mod_y, c='blue', alpha=0.6, s=50, edgecolors='darkblue', 
                      linewidth=0.5, label='Moderate (n=59, Index 20-60)')
scatter3 = ax.scatter(high_x, high_y, c='green', alpha=0.6, s=50, edgecolors='darkgreen', 
                      linewidth=0.5, label='High (n=47, Index≥60)')

# Add cluster center markers
cluster_centers_x = [50, 50, 50]
cluster_centers_y = [10, 40, 75]
ax.scatter(cluster_centers_x, cluster_centers_y, c='black', s=200, marker='X', 
          edgecolors='yellow', linewidth=2, zorder=5, label='Cluster Centers')

# Add horizontal lines to show cluster boundaries
ax.axhline(y=20, color='gray', linestyle='--', linewidth=1.5, alpha=0.5)
ax.axhline(y=60, color='gray', linestyle='--', linewidth=1.5, alpha=0.5)

# Customize plot
ax.set_xlabel('Feature Usage Distribution', fontsize=12, fontweight='bold')
ax.set_ylabel('Engagement Index (0-100)', fontsize=12, fontweight='bold')
ax.set_title('K-Means Clustering: User Engagement Segmentation (k=3)', 
            fontsize=13, fontweight='bold', pad=15)
ax.set_xlim(-5, 105)
ax.set_ylim(-5, 105)
ax.grid(True, alpha=0.3, linestyle=':')

# Add legend outside the plot area at top left
ax.legend(loc='upper left', bbox_to_anchor=(0, 1), frameon=True, 
          fancybox=True, shadow=True, fontsize=10)

plt.tight_layout()

# Save as PDF and PNG
plt.savefig('CHART_CLUSTERING.pdf', format='pdf', bbox_inches='tight', dpi=300)
plt.savefig('CHART_CLUSTERING.png', format='png', bbox_inches='tight', dpi=300)
print("Clustering scatter plot saved as CHART_CLUSTERING.pdf and CHART_CLUSTERING.png")

plt.show()
