const fs = require('fs')

const eslintReport = JSON.parse(fs.readFileSync(process.argv[2], 'utf8'))

const gitlabReport = {
    description: 'ESLint Report',
    messages: [],
}

eslintReport.forEach((file) => {
    file.messages.forEach((message) => {
        gitlabReport.messages.push({
            description: message.message,
            severity: message.severity === 2 ? 'error' : 'warning',
            fingerprint: `${file.filePath}-${message.line}-${message.column}`,
            location: {
                path: file.filePath,
                lines: { begin: message.line, end: message.line },
            },
        })
    })
})

fs.writeFileSync('gl-eslint-quality-report.json', JSON.stringify(gitlabReport, null, 2))
