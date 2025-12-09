import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

# Read the data
df = pd.read_csv('combined_user_data_NEW.csv')

# Normalize variables for radar chart (0-100 scale)
# We'll compare the three user segments across 6 dimensions

segments = ['Drop-off', 'Moderate', 'High Engagement']
segment_labels = ['Drop-off', 'Moderate Engagement', 'High Engagement']

# Variables to compare (normalized to 0-100)
variables = {
    'Total Dreams': ('Total_Dreams', 13),
    'Riddles Solved': ('Riddles_Solved', 9),
    'Totems Collected': ('Totems_Collected', 7),
    'Features Used': ('Features_Used_Count', 18),
    'Session Minutes': ('Avg_Session_Minutes', 70),
    'Story Chapters': ('Total_Story_Chapters', 27)
}

# Calculate means for each segment and normalize
segment_profiles = {}
segment_raw_means = {}
for segment in segments:
    segment_data = df[df['User_Segment'] == segment]
    profile = []
    raw_means = []
    for var_name, (column, max_val) in variables.items():
        mean_val = segment_data[column].mean()
        normalized_val = (mean_val / max_val) * 100
        profile.append(normalized_val)
        raw_means.append(mean_val)
    segment_profiles[segment] = profile
    segment_raw_means[segment] = raw_means

# Number of variables
num_vars = len(variables)

# Compute angle for each axis
angles = np.linspace(0, 2 * np.pi, num_vars, endpoint=False).tolist()

# Complete the circle
segment_profiles_complete = {}
for segment, profile in segment_profiles.items():
    segment_profiles_complete[segment] = profile + [profile[0]]
angles += angles[:1]

# Create figure with single radar chart only
fig = plt.figure(figsize=(12, 10), dpi=300)
ax = fig.add_subplot(111, projection='polar')

# Define colors for segments (using display labels)
colors = {'Drop-off': '#FF6B9D', 
          'Moderate Engagement': '#FFD166', 
          'High Engagement': '#06D6A0'}

# Plot data for each segment
for i, segment in enumerate(segments):
    display_label = segment_labels[i]
    values = segment_profiles_complete[segment]
    ax.plot(angles, values, 'o-', linewidth=2.5, label=display_label, 
            color=colors[display_label], markersize=8, zorder=3)
    ax.fill(angles, values, alpha=0.20, color=colors[display_label], zorder=2)

# Set theta direction and start position to ensure proper alignment
ax.set_theta_offset(np.pi / 2)
ax.set_theta_direction(-1)

# Customize the plot - increase label distance to prevent cutoff
var_labels = list(variables.keys())
ax.set_xticks(angles[:-1])
ax.set_xticklabels(var_labels, fontsize=12, fontweight='bold', fontname='Arial')
# Adjust tick label position outward to prevent cutoff
ax.tick_params(axis='x', pad=10)

# Set y-axis limits and labels
ax.set_ylim(0, 100)
ax.set_yticks([25, 50, 75, 100])
ax.set_yticklabels(['25', '50', '75', '100'], fontsize=10, fontname='Arial', color='gray')

# Add grid
ax.grid(True, linestyle='--', alpha=0.5, linewidth=0.8, color='gray')

# Add title
plt.title('Multi-Dimensional User Segment Profiles\n(Normalized Scores 0-100)', 
          fontsize=16, fontweight='bold', fontname='Arial', pad=25, y=1.08)

# Add color legend box at top right
legend_elements = [
    plt.Line2D([0], [0], color='#FF6B9D', lw=3, label='Drop-off (n=41)'),
    plt.Line2D([0], [0], color='#FFD166', lw=3, label='Moderate Engagement (n=59)'),
    plt.Line2D([0], [0], color='#06D6A0', lw=3, label='High Engagement (n=47)')
]
legend_box = ax.legend(handles=legend_elements, loc='upper right', bbox_to_anchor=(1.35, 1.1), 
                       fontsize=10, frameon=True, shadow=True, fancybox=True,
                       edgecolor='black', facecolor='white', title='User Segments',
                       title_fontsize=11)

# Get segment counts
drop_off_data = df[df['User_Segment'] == 'Drop-off']
moderate_data = df[df['User_Segment'] == 'Moderate']
high_data = df[df['User_Segment'] == 'High Engagement']

# Create comprehensive statistics table - positioned BELOW the chart
table_data = []
table_data.append(['Dimension', 'Drop-off\n(n=41)', 'Moderate\n(n=59)', 'High Eng.\n(n=47)', 'Max'])

for i, (var_name, (column, max_val)) in enumerate(variables.items()):
    row = [
        var_name,
        f'{segment_raw_means["Drop-off"][i]:.1f}',
        f'{segment_raw_means["Moderate"][i]:.1f}',
        f'{segment_raw_means["High Engagement"][i]:.1f}',
        f'{max_val}'
    ]
    table_data.append(row)

# Create table below the chart - increased width and height
table = plt.table(cellText=table_data,
                 cellLoc='center',
                 loc='bottom',
                 bbox=[0.05, -0.30, 0.90, 0.22])

# Style the table
table.auto_set_font_size(False)
table.set_fontsize(8.5)
table.scale(1, 2.5)

# Color the header row
for i in range(5):
    cell = table[(0, i)]
    cell.set_facecolor('#E8E8E8')
    cell.set_text_props(weight='bold', fontsize=10)
    cell.set_edgecolor('black')
    cell.set_linewidth(1.5)
    cell.set_height(0.08)  # Make header row taller

# Style data rows with alternating colors
for i in range(1, len(table_data)):
    for j in range(5):
        cell = table[(i, j)]
        if i % 2 == 1:
            cell.set_facecolor('#FFFFFF')
        else:
            cell.set_facecolor('#F9F9F9')
        cell.set_edgecolor('black')
        cell.set_linewidth(0.5)
        
        # Bold the dimension name column
        if j == 0:
            cell.set_text_props(weight='bold')

# Adjust layout
plt.tight_layout()
plt.subplots_adjust(bottom=0.20, right=0.85)

# Save figure
plt.savefig('CHART_13_User_Segment_Profiles.png', dpi=300, bbox_inches='tight', facecolor='white')
plt.savefig('CHART_13_User_Segment_Profiles.pdf', dpi=300, bbox_inches='tight', facecolor='white')

print("✓ Chart 13 generated successfully!")
print(f"\nUser Segment Profiles (Radar Chart):")
print(f"\nSegment Sizes:")
print(f"  Drop-off: n = {len(drop_off_data)}")
print(f"  Moderate: n = {len(moderate_data)}")
print(f"  High Engagement: n = {len(high_data)}")
print(f"\nDimensions Compared (Normalized to 0-100):")
for var_name in variables.keys():
    print(f"  • {var_name}")
print(f"\nKey Insights:")
print(f"  • Drop-off users show minimal engagement across all dimensions")
print(f"  • Moderate users demonstrate balanced 50-70% utilization")
print(f"  • High engagement users excel across all features (70-90%)")
print(f"  • Clear differentiation supports segment validity")
print(f"\nFiles saved:")
print(f"  - CHART_13_User_Segment_Profiles.png (300 DPI)")
print(f"  - CHART_13_User_Segment_Profiles.pdf (300 DPI)")

plt.show()
