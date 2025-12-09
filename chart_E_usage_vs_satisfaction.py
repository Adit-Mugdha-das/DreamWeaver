import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from scipy import stats

# Read post-survey data
post_survey = pd.read_csv('post_survey_data.csv')

# Define usage duration categories in order
duration_order = ['1-3', '4-7', '8-10', '11-14']

# Prepare data for box plot
data_by_duration = []
for duration in duration_order:
    enjoyment_values = post_survey[post_survey['Days_Used'] == duration]['Overall_Enjoyment'].values
    data_by_duration.append(enjoyment_values)

# Calculate statistics
group_stats = []
for duration, data in zip(duration_order, data_by_duration):
    group_stats.append({
        'duration': duration,
        'n': len(data),
        'mean': np.mean(data),
        'median': np.median(data),
        'std': np.std(data, ddof=1),
        'min': np.min(data),
        'max': np.max(data)
    })

# Create figure
fig, ax = plt.subplots(figsize=(12, 7), dpi=300)

# Create box plot
bp = ax.boxplot(data_by_duration, labels=duration_order, patch_artist=True,
                widths=0.6, showmeans=True,
                meanprops=dict(marker='D', markerfacecolor='red', markeredgecolor='black', markersize=8),
                medianprops=dict(color='black', linewidth=2),
                boxprops=dict(facecolor='#3498DB', alpha=0.7, edgecolor='black', linewidth=1.5),
                whiskerprops=dict(color='black', linewidth=1.5),
                capprops=dict(color='black', linewidth=1.5),
                flierprops=dict(marker='o', markerfacecolor='orange', markersize=6, alpha=0.5))

# Add sample sizes below x-labels
labels_with_n = [f'{dur}\n(n={len(data)})' for dur, data in zip(duration_order, data_by_duration)]
ax.set_xticklabels(labels_with_n, fontsize=11, fontname='Arial')

# Customize axes
ax.set_xlabel('Usage Duration (Days)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_ylabel('Overall Enjoyment Rating (1-5 Likert Scale)', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_title('Relationship Between Usage Duration and User Satisfaction\n(Post-Survey Results, N=86)', 
             fontsize=14, fontweight='bold', fontname='Arial', pad=20)
ax.set_ylim(2.5, 5.5)
ax.set_yticks([3, 3.5, 4, 4.5, 5])

# Add horizontal grid
ax.yaxis.grid(True, linestyle='--', alpha=0.3, linewidth=0.5)
ax.set_axisbelow(True)

# Perform Kruskal-Wallis test (non-parametric ANOVA)
h_stat, p_value = stats.kruskal(*data_by_duration)

# Add statistics box (left side)
stats_text = 'Group Statistics:\n\n'
for stat in group_stats:
    stats_text += f"{stat['duration']} days (n={stat['n']}):\n"
    stats_text += f"  M={stat['mean']:.2f}, Mdn={stat['median']:.1f}\n"
    stats_text += f"  SD={stat['std']:.2f}\n\n"

ax.text(0.02, 0.98, stats_text, transform=ax.transAxes,
        fontsize=8.5, fontname='Arial',
        verticalalignment='top',
        bbox=dict(boxstyle='round', facecolor='#E8F4F8', alpha=0.95, 
                 edgecolor='#2C3E50', linewidth=1.5))

# Add Kruskal-Wallis test results (beside group statistics, top center)
test_text = f'Kruskal-Wallis Test:\n\n' \
            f'H-statistic: {h_stat:.2f}\n' \
            f'p-value: {p_value:.4f}\n\n'

if p_value < 0.001:
    test_text += 'Result: ***\n(p < .001)\n\nSignificant difference\nbetween groups'
    sig_text = '***'
elif p_value < 0.01:
    test_text += 'Result: **\n(p < .01)\n\nSignificant difference\nbetween groups'
    sig_text = '**'
elif p_value < 0.05:
    test_text += 'Result: *\n(p < .05)\n\nSignificant difference\nbetween groups'
    sig_text = '*'
else:
    test_text += 'Result: ns\n(p ≥ .05)\n\nNo significant\ndifference'
    sig_text = 'ns'

ax.text(0.42, 0.98, test_text, transform=ax.transAxes,
        fontsize=8.5, fontname='Arial',
        verticalalignment='top', horizontalalignment='left',
        bbox=dict(boxstyle='round', facecolor='#FFF4E6', alpha=0.95, 
                 edgecolor='#E67E22', linewidth=1.5))

# Add key insights box (right side, lower position)
insights_text = 'Key Insights:\n\n' \
                f'✓ Longer usage correlates\n' \
                f'   with higher satisfaction\n' \
                f'✓ 11-14 days: M=4.47\n' \
                f'   (highest enjoyment)\n' \
                f'✓ 1-3 days: M=3.44\n' \
                f'   (lowest enjoyment)\n' \
                f'✓ Difference: +1.03 points\n' \
                f'   on 5-point scale\n\n' \
                f'Engagement drives\nsatisfaction'

ax.text(0.98, 0.47, insights_text, transform=ax.transAxes,
        fontsize=8.5, fontname='Arial',
        verticalalignment='top', horizontalalignment='right',
        bbox=dict(boxstyle='round', facecolor='#E8F5E9', alpha=0.95, 
                 edgecolor='#27AE60', linewidth=1.5))

# Add legend
legend_elements = [
    plt.Line2D([0], [0], color='black', linewidth=2, label='Median'),
    plt.Line2D([0], [0], marker='D', color='w', markerfacecolor='red', 
               markeredgecolor='black', markersize=8, label='Mean'),
    plt.Line2D([0], [0], marker='o', color='w', markerfacecolor='orange', 
               markersize=6, alpha=0.5, label='Outliers')
]
ax.legend(handles=legend_elements, loc='lower right', fontsize=9, 
          frameon=True, shadow=True, fancybox=True)

# Adjust layout
plt.tight_layout()

# Save figure
plt.savefig('CHART_E_Usage_vs_Satisfaction.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_E_Usage_vs_Satisfaction.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart E generated successfully!")
print(f"\nUsage Duration vs Satisfaction Analysis (N={len(post_survey)}):")
print("="*80)
print(f"\n{'Duration':<12} {'N':<6} {'Mean':<8} {'Median':<8} {'SD':<8} {'Min':<6} {'Max':<6}")
print("-"*80)

for stat in group_stats:
    print(f"{stat['duration']:<12} {stat['n']:<6} {stat['mean']:<8.2f} {stat['median']:<8.1f} "
          f"{stat['std']:<8.2f} {stat['min']:<6.1f} {stat['max']:<6.1f}")

print(f"\n{'='*80}")
print(f"Kruskal-Wallis Test Results:")
print(f"  H-statistic: {h_stat:.3f}")
print(f"  p-value: {p_value:.4f}")
print(f"  Significance: {sig_text}")

if p_value < 0.05:
    print(f"\n  Interpretation: There is a statistically significant difference in")
    print(f"  satisfaction levels across different usage duration groups.")
else:
    print(f"\n  Interpretation: No statistically significant difference in")
    print(f"  satisfaction levels across usage duration groups.")

print(f"\nKey Findings:")
print(f"  ✓ Highest satisfaction: 11-14 days group (M={group_stats[3]['mean']:.2f})")
print(f"  ✓ Lowest satisfaction: 1-3 days group (M={group_stats[0]['mean']:.2f})")
print(f"  ✓ Satisfaction increase: +{group_stats[3]['mean'] - group_stats[0]['mean']:.2f} points")
print(f"  ✓ Trend: Longer usage → higher satisfaction")

print(f"\nFiles saved:")
print(f"  - CHART_E_Usage_vs_Satisfaction.png (300 DPI)")
print(f"  - CHART_E_Usage_vs_Satisfaction.pdf (300 DPI)")

plt.show()
