const fs = require('fs')

const stylelintReport = JSON.parse(fs.readFileSync(process.argv[2], 'utf8'))

const gitlabReport = {
    description: 'Stylelint Report',
    messages: [],
}

stylelintReport.forEach((file) => {
    file.warnings.forEach((warning) => {
        gitlabReport.messages.push({
            description: warning.text,
            severity: warning.severity,
            fingerprint: `${file.source}-${warning.line}-${warning.column}`,
            location: {
                path: file.source,
                lines: { begin: warning.line, end: warning.line },
            },
        })
    })
})

fs.writeFileSync('gl-stylelint-quality-report.json', JSON.stringify(gitlabReport, null, 2))
