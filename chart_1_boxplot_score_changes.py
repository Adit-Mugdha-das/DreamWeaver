"""
CHART 1: RETENTION FUNNEL ⭐ HIGH PRIORITY
==========================================
Publication-quality visualization for Elsevier Entertainment Computing
Shows user journey and critical drop-off points

Following: FINAL_VISUALIZATION_PLAN.txt - TIER 1, CHART 1
Author: DreamWeaver Research Team
Date: December 9, 2025
"""

import pandas as pd
import matplotlib.pyplot as plt
import matplotlib.patches as mpatches
import numpy as np

# No seaborn needed for funnel chart
# plt.style.use('seaborn-v0_8-whitegrid')

# Configure for high-resolution publication
plt.rcParams['figure.dpi'] = 300
plt.rcParams['savefig.dpi'] = 300
plt.rcParams['font.family'] = 'sans-serif'
plt.rcParams['font.sans-serif'] = ['Arial', 'Helvetica', 'DejaVu Sans']
plt.rcParams['font.size'] = 11
plt.rcParams['axes.labelsize'] = 12
plt.rcParams['axes.titlesize'] = 13
plt.rcParams['xtick.labelsize'] = 11
plt.rcParams['ytick.labelsize'] = 11
plt.rcParams['legend.fontsize'] = 10
plt.rcParams['figure.titlesize'] = 14

print("="*70)
print("CHART 1: RETENTION FUNNEL - User Journey Visualization")
print("="*70)

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

# Calculate drop-off percentages
dropoffs = []
for i in range(len(percentages)-1):
    drop = percentages[i] - percentages[i+1]
    dropoffs.append(drop)
dropoffs.append(0)  # Last stage has no further drop

print(f"  Total stages: {len(stages)}")
print(f"  Starting users: {user_counts[0]}")
print(f"  Final retained: {user_counts[-1]} ({percentages[-1]}%)")

# Identify biggest drop-off
max_drop_idx = dropoffs.index(max(dropoffs))
print(f"  Biggest drop-off: {max(dropoffs):.1f}% at stage '{stages[max_drop_idx]}'")

# Create figure
print("\n[Step 2/5] Creating funnel visualization...")
fig, ax = plt.subplots(figsize=(12, 8))

plt.show()

print("\n" + "="*70)
print("CHART 1 COMPLETE!")
print("="*70)
print("\n📊 FIGURE CAPTION FOR PAPER:")
print("-" * 70)
print("""
Figure 1. Distribution of Evolution and Health score changes across three 
engagement segments (N=147). Panel (A) shows evolution score changes and 
Panel (B) shows health score changes measured from Day 1 to Day 14. 
High-engagement users (n=47) showed significant positive changes (Evolution: 
M=27.15, SD=21.16; Health: M=28.91, SD=21.91), while drop-off users (n=41) 
showed negative changes (Evolution: M=-21.85; Health: M=-22.51). Moderate 
users (n=59) showed intermediate gains. Box boundaries represent 25th and 
75th percentiles; whiskers extend to 1.5×IQR. Blue/green diamonds indicate 
mean values. One-way ANOVA revealed significant differences across segments, 
F(2,144) = 422.74, p < .001.
""")
print("-" * 70)

# Generate summary statistics table
print("\n📈 SUMMARY STATISTICS FOR TABLE:")
print("-" * 70)
print(f"{'Segment':<20} {'Evolution M(SD)':<20} {'Health M(SD)':<20}")
print("-" * 70)
for segment in segment_order:
    seg_data = df[df['User_Segment'] == segment]
    evo_m = seg_data['Evolution_Score_Change'].mean()
    evo_sd = seg_data['Evolution_Score_Change'].std()
    health_m = seg_data['Health_Score_Change'].mean()
    health_sd = seg_data['Health_Score_Change'].std()
    n = len(seg_data)
    print(f"{segment:<20} {evo_m:>6.2f} ({evo_sd:>5.2f})     {health_m:>6.2f} ({health_sd:>5.2f})")
print("-" * 70)

print("\n✅ Next: Run chart_2_correlation_heatmap.py")
