# Prompt: Generate API Endpoint

Use this prompt when the backend dev needs AI to generate a new API endpoint.

## Template

```
Create a new API endpoint for [feature].

Model: [model name, fields]
Route: [method] /api/[path]

1. Create the model in api/models/ with PDO queries for:
   - list (with pagination)
   - get by id
   - create
   - update
   - delete

2. Create the controller in api/controllers/ with:
   - Input validation
   - Error handling
   - JSON responses matching the API contract format

3. Register the route in api/routes/api.php

4. Update docs/api-contract.md with the new endpoint spec

Follow PSR-12, declare(strict_types=1), thin controllers.
```
