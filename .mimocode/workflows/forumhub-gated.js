// ForumHub Centralized Workflow — Role-Gated Development
//
// Usage: workflow({ operation: "run", script: ".mimocode/workflows/forumhub-gated.js", args: { task: "...", role: "frontend|backend" } })
//
// Phases:
//   0. Role Validation (asks if role not provided)
//   1. Scope & Guardrail Check
//   2. Implementation (parallel where possible)
//   3. Verification
//   4. Report

export const meta = {
  name: "forumhub-gated",
  description: "Centralized role-gated workflow for ForumHub with layer ownership enforcement",
};

const DOMAINS = {
  frontend: { allow: ["frontend/", "public/"], deny: ["api/", "database/"], label: "Frontend Dev" },
  backend:  { allow: ["api/", "database/"],    deny: ["frontend/", "public/"], label: "Backend Dev"  },
};

function resolveRole(raw) {
  const r = String(raw).toLowerCase().trim();
  if (r.startsWith("front") || r === "fe") return "frontend";
  if (r.startsWith("back") || r === "be") return "backend";
  return null;
}

export default async function main(args) {
  // ── Phase 0: Role Validation ──
  phase("Phase 0 — Role Validation");

  let role = resolveRole(args.role);
  if (!role) {
    log("Role not specified or unrecognized. Ask the user which role they are.");
    log('Pass args.role="frontend" or args.role="backend"');
    return { status: "blocked", reason: "role_unknown" };
  }

  const domain = DOMAINS[role];
  log(`✅ Role validated: ${domain.label}`);
  log(`   Allowed: ${domain.allow.join(", ")}`);
  log(`   Denied:  ${domain.deny.join(", ")}`);

  // ── Phase 1: Scope Check ──
  phase("Phase 1 — Scope & Guardrail Check");

  // If the task description mentions files in the denied domain, flag it
  const taskText = String(args.task ?? "").toLowerCase();
  for (const denied of domain.deny) {
    if (taskText.includes(denied.toLowerCase().replace("/", "")) || taskText.includes(denied.toLowerCase())) {
      log(`⛔ GUARDRAIL: Task references '${denied}' which is owned by the other developer.`);
      log(`   As ${domain.label}, you cannot touch ${denied}.`);
      log("   Implement only your side and generate a cross-layer coordination note.");
    }
  }

  // Check existing coordination notes
  const notes = await glob("docs/coordination/*.md");
  if (notes.length > 0) {
    log(`📋 Found ${notes.length} cross-layer coordination note(s) — check them before starting.`);
    for (const note of notes) {
      const content = await readFile(note);
      if (content) log(`   ${note}: ${content.split("\n")[0]}`);
    }
  }

  // ── Phase 2: Implementation ──
  phase("Phase 2 — Implementation");

  log(`Starting implementation for: ${args.task}`);
  // The actual implementation work is delegated to agents in the calling context
  // This workflow focuses on orchestration and gating

  // Check existing files before editing
  const filesToCheck = args.files ?? [];
  for (const f of filesToCheck) {
    const allowed = domain.allow.some((a) => f.startsWith(a)) || f.startsWith(".mimocode") || f.startsWith("docs/");
    if (!allowed) {
      log(`⛔ BLOCKED: ${f} is outside ${domain.label}'s domain.`);
      return { status: "blocked", reason: `file_out_of_domain: ${f}` };
    }
    const exists = await exists(f);
    log(`   ${exists ? "📄" : "🆕"} ${f}`);
  }

  // ── Phase 3: Verification ──
  phase("Phase 3 — Verification");

  // File length check
  for (const f of filesToCheck) {
    if (f.endsWith(".php") || f.endsWith(".jsx") || f.endsWith(".js") || f.endsWith(".css")) {
      try {
        const content = await readFile(f);
        if (content) {
          const lines = content.split("\n").length;
          if (lines > 200) {
            log(`⚠️  ${f} has ${lines} lines (limit 200). Split before committing.`);
          } else {
            log(`✅ ${f}: ${lines} lines (OK)`);
          }
        }
      } catch {
        // file doesn't exist yet (new file)
      }
    }
  }

  // ── Phase 4: Report ──
  phase("Phase 4 — Summary");

  const summary = {
    status: "success",
    role: domain.label,
    task: args.task,
    files: filesToCheck,
  };

  log(`✅ Workflow complete.`);
  log(`   Role: ${domain.label}`);
  log(`   Task: ${args.task}`);

  return summary;
}
