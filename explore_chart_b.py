import pandas as pd

post = pd.read_csv('post_survey_data.csv')

print('Post-survey columns:')
print(post.columns.tolist())

print('\nSatisfaction variables stats:')
vars = ['Easy_to_Use', 'Meaningful_Insights', 'Story_Engagement', 'Motivated_Explore', 'Overall_Enjoyment']

for v in vars:
    print(f'\n{v}:')
    print(post[v].value_counts().sort_index())
    print(f'Mean: {post[v].mean():.2f}, SD: {post[v].std():.2f}')
