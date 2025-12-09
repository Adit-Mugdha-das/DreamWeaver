"""
CHART 1: RETENTION FUNNEL ⭐ HIGH PRIORITY
==========================================
Publication-quality visualization for Elsevier Entertainment Computing
Shows user journey and critical drop-off points

Following: FINAL_VISUALIZATION_PLAN.txt - TIER 1, CHART 1
Author: DreamWeaver Research Team
Date: December 9, 2025
"""

import matplotlib.pyplot as plt
import matplotlib.patches as mpatches
import numpy as np

# Configure for high-resolution publication
plt.rcParams['figure.dpi'] = 300
plt.rcParams['savefig.dpi'] = 300
plt.rcParams['font.family'] = 'sans-serif'
plt.rcParams['font.sans-serif'] = ['Arial', 'Helvetica', 'DejaVu Sans']
plt.rcParams['font.size'] = 11
plt.rcParams['axes.labelsize'] = 12
plt.rcParams['axes.titlesize'] = 14
plt.rcParams['xtick.labelsize'] = 11
plt.rcParams['ytick.labelsize'] = 11
plt.rcParams['legend.fontsize'] = 10

print("="*80)
print("CHART 1: RETENTION FUNNEL - User Journey Visualization")
print("="*80)

# Funnel data from statistical_results.txt - Section 6
print("\n[Step 1/5] Setting up funnel stage data...")
stages = [
    'Registered',
    'Logged ≥1 Dream',
    'Viewed Dream DNA',
    'Played Story Mode',
    'Solved ≥1 Riddle',
    'Active After Week 2'
]

user_counts = [147, 144, 116, 119, 110, 102]
percentages = [100.0, 98.0, 78.9, 81.0, 74.8, 69.4]

# Calculate drop-off percentages between stages
dropoffs = []
for i in range(len(percentages)-1):
    drop = percentages[i] - percentages[i+1]
    dropoffs.append(drop)

print(f"  Total stages: {len(stages)}")
print(f"  Starting users: {user_counts[0]}")
print(f"  Final retained: {user_counts[-1]} ({percentages[-1]}%)")

# Identify biggest drop-off (between Logged Dream and DNA View)
print(f"\n  Drop-offs between stages:")
for i in range(len(dropoffs)):
    print(f"    {stages[i]} → {stages[i+1]}: -{dropoffs[i]:.1f}%")
max_drop = max(dropoffs)
max_drop_idx = dropoffs.index(max_drop)
print(f"\n  🔴 BIGGEST DROP: {max_drop:.1f}% between '{stages[max_drop_idx]}' and '{stages[max_drop_idx+1]}'")

# Create figure with stepped horizontal bars (funnel effect)
print("\n[Step 2/5] Creating funnel visualization...")
fig, ax = plt.subplots(figsize=(14, 10))

# Define beautiful color gradient - professional and vibrant
# Use a custom gradient from green (success) to orange (attrition)
colors = ['#27AE60', '#2ECC71', '#F39C12', '#E67E22', '#E74C3C', '#C0392B']  # Green to Red gradient

# Y positions for horizontal bars
y_positions = np.arange(len(stages))[::-1]  # Reverse so top is first stage

# Draw horizontal bars (funnel effect)
bars = ax.barh(y_positions, percentages, height=0.65, color=colors, 
               edgecolor='#2C3E50', linewidth=2.5, alpha=0.9)

# Annotate bars with counts and percentages
print("\n[Step 3/5] Adding annotations...")
for i, (bar, count, pct, stage) in enumerate(zip(bars, user_counts, percentages, stages)):
    # Use black text for all bars for consistency
    text_color = 'black'
    
    # Add percentage inside bar with shadow effect for better visibility
    ax.text(pct/2, y_positions[i], f'{pct:.1f}%', 
            ha='center', va='center', fontsize=16, fontweight='bold', 
            color=text_color,
            bbox=dict(boxstyle='round,pad=0.4', facecolor='white', 
                     edgecolor='none', alpha=0.3))
    
    # Add user count at end of bar with background for visibility
    ax.text(pct + 3, y_positions[i], f'n = {count}', 
            ha='left', va='center', fontsize=12, fontweight='bold',
            color='#2C3E50',
            bbox=dict(boxstyle='round,pad=0.3', facecolor='#ECF0F1', 
                     edgecolor='#95A5A6', linewidth=1, alpha=0.9))
    
    # Add drop-off annotation between stages
    if i < len(stages) - 1:
        drop_pct = dropoffs[i]
        if drop_pct > 0:
            # Arrow showing drop with better styling
            y_mid = (y_positions[i] + y_positions[i+1]) / 2
            ax.annotate(f'↓ {drop_pct:.1f}%', 
                       xy=(percentages[i+1] + 2, y_positions[i+1] + 0.2),
                       xytext=(percentages[i] - 2, y_positions[i] - 0.2),
                       arrowprops=dict(arrowstyle='->', color='#E74C3C', 
                                      lw=2.5, shrinkA=5, shrinkB=5),
                       fontsize=11, color='#C0392B', fontweight='bold',
                       ha='center',
                       bbox=dict(boxstyle='round,pad=0.3', facecolor='#FADBD8', 
                                edgecolor='#E74C3C', linewidth=1.5))

# Highlight biggest drop-off
biggest_drop_y = (y_positions[max_drop_idx] + y_positions[max_drop_idx+1]) / 2
ax.text(15, biggest_drop_y, '🚨 CRITICAL DROP-OFF', 
        fontsize=13, fontweight='bold', color='#8E44AD',
        bbox=dict(boxstyle='round,pad=0.6', facecolor='#F9E79F', 
                 edgecolor='#E74C3C', linewidth=2.5, alpha=0.95))

# Styling
ax.set_yticks(y_positions)
ax.set_yticklabels(stages, fontsize=13, fontweight='bold', color='#2C3E50')
ax.set_xlabel('Retention Rate (%)', fontsize=14, fontweight='bold', color='#2C3E50')
ax.set_title('User Retention Funnel: DreamWeaver 14-Day Journey (N=147)',
            fontsize=17, fontweight='bold', pad=20, color='#2C3E50')
ax.set_xlim(0, 115)
ax.grid(axis='x', alpha=0.25, linestyle='--', linewidth=0.8, color='#BDC3C7')
ax.set_axisbelow(True)
ax.set_facecolor('#FDFEFE')

# Add vertical reference lines with better styling
ax.axvline(x=100, color='#27AE60', linestyle='--', linewidth=2.5, 
          alpha=0.6, label='100% (All Registered)')
ax.axvline(x=69.4, color='#E74C3C', linestyle='--', linewidth=2.5, 
          alpha=0.6, label='69.4% (Week 2 Active)')

# Add legend with better styling
ax.legend(loc='lower right', fontsize=12, framealpha=0.98, 
         edgecolor='#34495E', fancybox=True, shadow=True)

# Add summary text box with improved design
summary_text = f"""KEY INSIGHTS:
• Starting users: {user_counts[0]}
• Final retention: {user_counts[-1]} ({percentages[-1]}%)
• Largest drop-off: {max_drop:.1f}% 
  ({stages[max_drop_idx]} → {stages[max_drop_idx+1]})
• Total attrition: {100 - percentages[-1]:.1f}%"""

ax.text(0.015, 0.98, summary_text, transform=ax.transAxes,
        fontsize=11, verticalalignment='top', fontweight='normal',
        color='#2C3E50', family='monospace',
        bbox=dict(boxstyle='round,pad=0.8', facecolor='#D5F4E6', 
                 edgecolor='#27AE60', linewidth=2, alpha=0.95))

print("\n[Step 4/5] Finalizing layout...")
plt.tight_layout()

# Save the figure
print("\n[Step 5/5] Saving high-resolution figure...")
output_file = r'c:\xampp\htdocs\dreamweaver\CHART_1_Retention_Funnel.png'
plt.savefig(output_file, dpi=300, bbox_inches='tight', facecolor='white')
print(f"✓ Saved PNG: {output_file}")

# Also save as PDF for publication
output_pdf = r'c:\xampp\htdocs\dreamweaver\CHART_1_Retention_Funnel.pdf'
plt.savefig(output_pdf, format='pdf', bbox_inches='tight', facecolor='white')
print(f"✓ Saved PDF: {output_pdf}")

plt.show()

print("\n" + "="*80)
print("✅ CHART 1 COMPLETE!")
print("="*80)
print("\n📊 FIGURE CAPTION FOR PAPER:")
print("-" * 80)
print("""
Figure 1. User retention funnel showing progression through DreamWeaver features 
over 14 days (N=147). The largest drop-off (19.0%) occurred between first dream 
entry and DNA viewing, suggesting onboarding challenges at the dream interpretation 
stage. Horizontal bars represent retention percentage at each stage, with user 
counts displayed. Red arrows indicate drop-off rates between consecutive stages. 
Final retention rate was 69.4% (n=102).
""")
print("-" * 80)

print("\n📋 STAGE-BY-STAGE BREAKDOWN:")
print("-" * 80)
for i, (stage, count, pct) in enumerate(zip(stages, user_counts, percentages)):
    if i < len(dropoffs):
        print(f"{i+1}. {stage:<25} {count:>3} users ({pct:>5.1f}%) → Drop: -{dropoffs[i]:.1f}%")
    else:
        print(f"{i+1}. {stage:<25} {count:>3} users ({pct:>5.1f}%) → FINAL")
print("-" * 80)

print("\n✅ Next Chart: Run chart_2_correlation_heatmap.py")
