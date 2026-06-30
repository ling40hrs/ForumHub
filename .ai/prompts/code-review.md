# Prompt: Code Review

Use this prompt when AI reviews a diff or PR.

## Template

```
Review this diff against ForumHub standards.

Checklist (in order):

1. Layer purity
   - Does any file mix PHP + React in one file?
   - Does any file touch a domain outside PR author's layer?

2. Anti-patterns
   - Any @ts-ignore or eslint-disable?
   - Any raw SQL in a controller file?
   - Any useEffect used for data fetching?
   - Any axios imports?
   - Any placeholder code (// ... rest)?

3. Conventions
   - Do new PHP files have declare(strict_types=1)?
   - Do new React files use functional components (not classes)?
   - Are imports direct (no barrel/index files)?

4. File length
   - Does any changed file exceed 200 lines?

5. Quality
   - Is input validation present on new API endpoints?
   - Are loading/empty/error states handled in new components?

Report: ❌ or ✅ for each item. For ❌, include file:line.
```
