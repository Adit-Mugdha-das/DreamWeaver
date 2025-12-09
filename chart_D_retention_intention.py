import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

# Read post-survey data
post_survey = pd.read_csv('post_survey_data.csv')

# Get retention intention data
continue_counts = post_survey['Continue_Using'].value_counts()
recommend_counts = post_survey['Recommend'].value_counts()

# Ensure all categories exist (Yes, Maybe, No)
categories = ['Yes', 'Maybe', 'No']
continue_values = [continue_counts.get(cat, 0) for cat in categories]
recommend_values = [recommend_counts.get(cat, 0) for cat in categories]

# Calculate percentages
continue_pct = [(v / sum(continue_values)) * 100 for v in continue_values]
recommend_pct = [(v / sum(recommend_values)) * 100 for v in recommend_values]

# Create figure
fig, ax = plt.subplots(figsize=(12, 7), dpi=300)

# Set up bar positions
x = np.arange(len(categories))
width = 0.35

# Colors: Green for Yes, Orange for Maybe, Red for No
colors_continue = ['#2ECC71', '#F39C12', '#E74C3C']
colors_recommend = ['#27AE60', '#E67E22', '#C0392B']

# Create grouped bars
bars1 = ax.bar(x - width/2, continue_values, width, 
               label=f'Continue Using (N={sum(continue_values)})',
               color=colors_continue, edgecolor='black', linewidth=1, alpha=0.85)

bars2 = ax.bar(x + width/2, recommend_values, width, 
               label=f'Recommend to Others (N={sum(recommend_values)})',
               color=colors_recommend, edgecolor='black', linewidth=1, alpha=0.85)

# Add value labels on bars
for i, (bar1, bar2, cv, rv, cp, rp) in enumerate(zip(bars1, bars2, continue_values, 
                                                       recommend_values, continue_pct, recommend_pct)):
    # Continue Using labels
    ax.text(bar1.get_x() + bar1.get_width()/2., cv + 1,
            f'{cv}\n({cp:.1f}%)',
            ha='center', va='bottom', fontsize=10, fontweight='bold', fontname='Arial')
    
    # Recommend labels
    ax.text(bar2.get_x() + bar2.get_width()/2., rv + 1,
            f'{rv}\n({rp:.1f}%)',
            ha='center', va='bottom', fontsize=10, fontweight='bold', fontname='Arial')

# Customize axes
ax.set_ylabel('Number of Respondents', fontsize=12, fontweight='bold', fontname='Arial')
ax.set_title('User Retention Intention and Recommendation Likelihood\n(Post-Survey Results, N=86)', 
             fontsize=14, fontweight='bold', fontname='Arial', pad=20)
ax.set_xticks(x)
ax.set_xticklabels(categories, fontsize=12, fontweight='bold', fontname='Arial')
ax.set_ylim(0, max(max(continue_values), max(recommend_values)) + 10)

# Add horizontal grid
ax.yaxis.grid(True, linestyle='--', alpha=0.3, linewidth=0.5)
ax.set_axisbelow(True)

# Add legend
ax.legend(loc='upper right', fontsize=10, frameon=True, shadow=True, fancybox=True)

# Add retention statistics box
retention_text = 'Retention Metrics:\n\n' \
                 f'Continue Using:\n' \
                 f'  Yes: {continue_values[0]} ({continue_pct[0]:.1f}%)\n' \
                 f'  Maybe: {continue_values[1]} ({continue_pct[1]:.1f}%)\n' \
                 f'  No: {continue_values[2]} ({continue_pct[2]:.1f}%)\n\n' \
                 f'Recommend:\n' \
                 f'  Yes: {recommend_values[0]} ({recommend_pct[0]:.1f}%)\n' \
                 f'  Maybe: {recommend_values[1]} ({recommend_pct[1]:.1f}%)\n' \
                 f'  No: {recommend_values[2]} ({recommend_pct[2]:.1f}%)'

ax.text(0.02, 0.98, retention_text, transform=ax.transAxes,
        fontsize=9, fontname='Arial',
        verticalalignment='top',
        bbox=dict(boxstyle='round', facecolor='#E8F4F8', alpha=0.95, 
                 edgecolor='#2C3E50', linewidth=1.5))

# Add insights box
positive_continue = continue_pct[0] + continue_pct[1]
positive_recommend = recommend_pct[0] + recommend_pct[1]

insights_text = 'Key Insights:\n\n' \
                f'✓ {positive_continue:.1f}% likely to\n' \
                f'   continue using\n' \
                f'✓ {positive_recommend:.1f}% likely to\n' \
                f'   recommend\n' \
                f'✓ {continue_pct[0]:.1f}% definite\n' \
                f'   retention\n' \
                f'✓ {recommend_pct[0]:.1f}% definite\n' \
                f'   word-of-mouth\n\n' \
                'Strong retention\n& advocacy potential'

ax.text(0.98, 0.65, insights_text, transform=ax.transAxes,
        fontsize=8.5, fontname='Arial',
        verticalalignment='top', horizontalalignment='right',
        bbox=dict(boxstyle='round', facecolor='#FFF4E6', alpha=0.95, 
                 edgecolor='#E67E22', linewidth=1.5))

# Adjust layout
plt.tight_layout()

# Save figure
plt.savefig('CHART_D_Retention_Intention.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_D_Retention_Intention.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart D generated successfully!")
print(f"\nRetention Intention Analysis (N={len(post_survey)}):")
print("="*80)

print(f"\nContinue Using:")
for cat, val, pct in zip(categories, continue_values, continue_pct):
    print(f"  {cat:<8}: {val:>2} ({pct:>5.1f}%)")

print(f"\nRecommend to Others:")
for cat, val, pct in zip(categories, recommend_values, recommend_pct):
    print(f"  {cat:<8}: {val:>2} ({pct:>5.1f}%)")

print(f"\n{'='*80}")
print(f"Summary Statistics:")
print(f"  Positive Intent to Continue (Yes + Maybe): {positive_continue:.1f}%")
print(f"  Positive Intent to Recommend (Yes + Maybe): {positive_recommend:.1f}%")
print(f"  Definite Retention Rate (Continue = Yes): {continue_pct[0]:.1f}%")
print(f"  Net Promoter Score (NPS-like): {recommend_pct[0] - recommend_pct[2]:.1f}%")

print(f"\nKey Insights:")
print(f"  ✓ {continue_values[0]} users ({continue_pct[0]:.1f}%) definitely plan to continue")
print(f"  ✓ {recommend_values[0]} users ({recommend_pct[0]:.1f}%) will recommend DreamWeaver")
print(f"  ✓ Only {continue_values[2]} users ({continue_pct[2]:.1f}%) do not plan to continue")
print(f"  ✓ Strong word-of-mouth potential with {positive_recommend:.1f}% advocacy")

print(f"\nFiles saved:")
print(f"  - CHART_D_Retention_Intention.png (300 DPI)")
print(f"  - CHART_D_Retention_Intention.pdf (300 DPI)")

plt.show()
