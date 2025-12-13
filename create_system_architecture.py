"""
DreamWeaver System Architecture Diagram
Creates a professional architecture visualization for the paper
"""

import matplotlib.pyplot as plt
import matplotlib.patches as mpatches
from matplotlib.patches import FancyBboxPatch, FancyArrowPatch
import matplotlib.lines as mlines

# Create figure
fig, ax = plt.subplots(1, 1, figsize=(14, 10))
ax.set_xlim(0, 10)
ax.set_ylim(0, 10)
ax.axis('off')

# Color scheme
color_frontend = '#3498DB'  # Blue
color_backend = '#E74C3C'   # Red
color_ai = '#9B59B6'        # Purple
color_database = '#2ECC71'  # Green
color_external = '#F39C12'  # Orange

# Title
ax.text(5, 9.5, 'DreamWeaver System Architecture', 
        fontsize=18, fontweight='bold', ha='center', fontname='Arial')

# ============================================================================
# LAYER 1: USER INTERFACE / MOBILE APP (Top)
# ============================================================================
y_ui = 8.5

# Mobile App Layer
ui_box = FancyBboxPatch((0.5, y_ui-0.6), 9, 1.2, 
                        boxstyle="round,pad=0.1", 
                        edgecolor=color_frontend, facecolor=color_frontend, 
                        alpha=0.2, linewidth=2)
ax.add_patch(ui_box)
ax.text(5, y_ui+0.4, 'Mobile Application Layer (React Native)', 
        fontsize=12, fontweight='bold', ha='center', color=color_frontend)

# UI Components (11 features)
ui_components = [
    (1.2, y_ui-0.2, 'Dream\nLog'),
    (2.0, y_ui-0.2, 'DNA\nHelix'),
    (2.8, y_ui-0.2, 'Story\nMode'),
    (3.6, y_ui-0.2, 'Riddles'),
    (4.4, y_ui-0.2, 'Dream\nArt'),
    (5.2, y_ui-0.2, 'Mind\nmap'),
    (6.0, y_ui-0.2, 'Avatar'),
    (6.8, y_ui-0.2, 'Totems'),
    (7.6, y_ui-0.2, 'Audio'),
    (8.4, y_ui-0.2, 'Library'),
    (9.2, y_ui-0.2, 'Feed')
]

for x, y, label in ui_components:
    box = FancyBboxPatch((x-0.3, y-0.25), 0.6, 0.5, 
                         boxstyle="round,pad=0.04", 
                         edgecolor=color_frontend, facecolor='white', 
                         linewidth=1.5)
    ax.add_patch(box)
    ax.text(x, y, label, fontsize=7, ha='center', va='center', fontname='Arial')

# ============================================================================
# LAYER 2: API GATEWAY / BACKEND (Middle-Top)
# ============================================================================
y_api = 6.5

# Backend API Layer
api_box = FancyBboxPatch((0.5, y_api-0.6), 9, 1.2, 
                         boxstyle="round,pad=0.1", 
                         edgecolor=color_backend, facecolor=color_backend, 
                         alpha=0.2, linewidth=2)
ax.add_patch(api_box)
ax.text(5, y_api+0.4, 'Backend API Layer (Node.js / Express)', 
        fontsize=12, fontweight='bold', ha='center', color=color_backend)

# API Services
api_services = [
    (1.5, y_api-0.2, 'User Auth &\nSession Mgmt'),
    (3.0, y_api-0.2, 'Dream\nProcessing'),
    (4.5, y_api-0.2, 'Analytics &\nTracking'),
    (6.0, y_api-0.2, 'Gamification\nEngine'),
    (7.5, y_api-0.2, 'Content\nGeneration'),
    (9.0, y_api-0.2, 'Community\nFeed')
]

for x, y, label in api_services:
    box = FancyBboxPatch((x-0.55, y-0.25), 1.1, 0.5, 
                         boxstyle="round,pad=0.05", 
                         edgecolor=color_backend, facecolor='white', 
                         linewidth=1.5)
    ax.add_patch(box)
    ax.text(x, y, label, fontsize=7.5, ha='center', va='center', fontname='Arial')

# ============================================================================
# LAYER 3: AI/ML SERVICES (Middle)
# ============================================================================
y_ai = 4.5

# AI Services Layer
ai_box = FancyBboxPatch((0.5, y_ai-0.6), 9, 1.2, 
                        boxstyle="round,pad=0.1", 
                        edgecolor=color_ai, facecolor=color_ai, 
                        alpha=0.2, linewidth=2)
ax.add_patch(ai_box)
ax.text(5, y_ai+0.4, 'AI/ML Services Layer', 
        fontsize=12, fontweight='bold', ha='center', color=color_ai)

# AI Components
ai_components = [
    (2.0, y_ai-0.2, 'Dream DNA\n4 Gene Analysis\n(Gemini)'),
    (4.0, y_ai-0.2, 'Story Generation\n(Gemini AI)'),
    (6.0, y_ai-0.2, 'Dream Art\nGeneration\n(DALL-E 2)'),
    (8.0, y_ai-0.2, 'Pattern\nClustering\n(NLP)')
]

for x, y, label in ai_components:
    box = FancyBboxPatch((x-0.65, y-0.3), 1.3, 0.6, 
                         boxstyle="round,pad=0.05", 
                         edgecolor=color_ai, facecolor='white', 
                         linewidth=1.5)
    ax.add_patch(box)
    ax.text(x, y, label, fontsize=7.5, ha='center', va='center', fontname='Arial')

# ============================================================================
# LAYER 4: DATA LAYER (Middle-Bottom)
# ============================================================================
y_data = 2.5

# Database Layer
db_box = FancyBboxPatch((0.5, y_data-0.6), 9, 1.2, 
                        boxstyle="round,pad=0.1", 
                        edgecolor=color_database, facecolor=color_database, 
                        alpha=0.2, linewidth=2)
ax.add_patch(db_box)
ax.text(5, y_data+0.4, 'Data Storage Layer', 
        fontsize=12, fontweight='bold', ha='center', color=color_database)

# Database Components
db_components = [
    (2.0, y_data-0.2, 'User Data\n(MySQL)'),
    (4.0, y_data-0.2, 'Dreams &\nDNA Genes'),
    (6.0, y_data-0.2, 'Engagement\nMetrics'),
    (8.0, y_data-0.2, 'Media Files\n(Images/Audio)')
]

for x, y, label in db_components:
    box = FancyBboxPatch((x-0.65, y-0.25), 1.3, 0.5, 
                         boxstyle="round,pad=0.05", 
                         edgecolor=color_database, facecolor='white', 
                         linewidth=1.5)
    ax.add_patch(box)
    ax.text(x, y, label, fontsize=7.5, ha='center', va='center', fontname='Arial')

# ============================================================================
# LAYER 5: EXTERNAL SERVICES (Bottom)
# ============================================================================
y_ext = 0.8

# External Services
ext_services = [
    (2.0, y_ext, 'Google Gemini\n(Text Generation)'),
    (4.0, y_ext, 'OpenAI DALL-E\n(Image Generation)'),
    (6.0, y_ext, 'Laravel Auth\n(Session Mgmt)'),
    (8.0, y_ext, 'Cloud Storage\n(AWS S3)')
]

for x in [2.0, 4.0, 6.0, 8.0]:
    box = FancyBboxPatch((x-0.65, y_ext-0.25), 1.3, 0.5, 
                         boxstyle="round,pad=0.05", 
                         edgecolor=color_external, facecolor='white', 
                         linewidth=1.5, linestyle='--')
    ax.add_patch(box)

for x, y, label in ext_services:
    ax.text(x, y, label, fontsize=7.5, ha='center', va='center', 
            fontname='Arial', style='italic')

# ============================================================================
# ARROWS - Data Flow
# ============================================================================

# Mobile App -> Backend API (simplified to avoid clutter)
for x in [2.0, 4.4, 6.8, 9.0]:
    arrow = FancyArrowPatch((x, y_ui-0.6), (x, y_api+0.6),
                           arrowstyle='->', mutation_scale=15, 
                           linewidth=1.5, color='gray', alpha=0.5)
    ax.add_patch(arrow)

# Backend API -> AI Services
arrow1 = FancyArrowPatch((3.0, y_api-0.6), (2.0, y_ai+0.6),
                        arrowstyle='<->', mutation_scale=15, 
                        linewidth=2, color=color_ai, alpha=0.7)
ax.add_patch(arrow1)

arrow2 = FancyArrowPatch((7.5, y_api-0.6), (6.0, y_ai+0.6),
                        arrowstyle='<->', mutation_scale=15, 
                        linewidth=2, color=color_ai, alpha=0.7)
ax.add_patch(arrow2)

# Backend API -> Database
for x in [1.5, 3.0, 6.0]:
    arrow = FancyArrowPatch((x, y_api-0.6), (x+0.5, y_data+0.6),
                           arrowstyle='<->', mutation_scale=15, 
                           linewidth=1.5, color=color_database, alpha=0.7)
    ax.add_patch(arrow)

# AI Services -> External APIs
arrow3 = FancyArrowPatch((2.0, y_ai-0.7), (2.0, y_ext+0.5),
                        arrowstyle='<->', mutation_scale=15, 
                        linewidth=1.5, color=color_external, 
                        alpha=0.7, linestyle='--')
ax.add_patch(arrow3)

arrow4 = FancyArrowPatch((4.0, y_ai-0.7), (2.0, y_ext+0.5),
                        arrowstyle='<->', mutation_scale=15, 
                        linewidth=1.5, color=color_external, 
                        alpha=0.7, linestyle='--')
ax.add_patch(arrow4)

arrow5 = FancyArrowPatch((6.0, y_ai-0.7), (4.0, y_ext+0.5),
                        arrowstyle='<->', mutation_scale=15, 
                        linewidth=1.5, color=color_external, 
                        alpha=0.7, linestyle='--')
ax.add_patch(arrow5)

# ============================================================================
# LEGEND
# ============================================================================
legend_elements = [
    mpatches.Patch(facecolor=color_frontend, alpha=0.3, edgecolor=color_frontend, 
                   linewidth=2, label='Frontend (Mobile)'),
    mpatches.Patch(facecolor=color_backend, alpha=0.3, edgecolor=color_backend, 
                   linewidth=2, label='Backend Services'),
    mpatches.Patch(facecolor=color_ai, alpha=0.3, edgecolor=color_ai, 
                   linewidth=2, label='AI/ML Processing'),
    mpatches.Patch(facecolor=color_database, alpha=0.3, edgecolor=color_database, 
                   linewidth=2, label='Data Storage'),
    mlines.Line2D([], [], color=color_external, marker='s', linestyle='--', 
                  markersize=8, label='External APIs', markerfacecolor='white', 
                  markeredgecolor=color_external, linewidth=2)
]

ax.legend(handles=legend_elements, loc='lower right', fontsize=9, 
          framealpha=0.9, edgecolor='gray')

# Save figure
plt.tight_layout()
plt.savefig('FIGURE_SYSTEM_ARCHITECTURE.png', dpi=300, bbox_inches='tight', 
            facecolor='white', edgecolor='none')
plt.savefig('FIGURE_SYSTEM_ARCHITECTURE.pdf', bbox_inches='tight', 
            facecolor='white', edgecolor='none')

print("✅ System Architecture diagram created successfully!")
print("📁 Files saved:")
print("   - FIGURE_SYSTEM_ARCHITECTURE.png (300 DPI)")
print("   - FIGURE_SYSTEM_ARCHITECTURE.pdf")
print("\n📝 Use in LaTeX:")
print("\\includegraphics[width=0.9\\textwidth]{FIGURE_SYSTEM_ARCHITECTURE.png}")
