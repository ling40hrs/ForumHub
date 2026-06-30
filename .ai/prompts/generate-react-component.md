# Prompt: Generate React Component

Use this prompt when the frontend dev needs AI to generate a new UI component or page.

## Template

```
Create a React component for [feature].

Spec:
- Route: [path]
- Data needed: [fields from API]
- States: loading, empty, error, normal

1. Create the page in frontend/src/pages/ (or component in components/)
2. Create a custom hook in frontend/src/hooks/ for data fetching
3. Handle loading, empty, error, and success states
4. Use Tailwind CSS for styling (brand palette from tailwind.config.js)
5. Use frontend/src/lib/fetch.js for API calls

Follow: functional component, hooks, no classes. JSX under 60 lines.
```
