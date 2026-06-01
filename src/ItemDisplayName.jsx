const ItemDisplayName = ({ item }) => {
  const baseName = String(item?.displayName || item?.name || 'Item').trim()
  const summary = String(item?.customFieldSummary || '').trim()
  const rawFields = Array.isArray(item?.customFields)
    ? item.customFields
    : Array.isArray(item?.custom_fields)
      ? item.custom_fields
      : []

  if (summary) {
    return (
      <div style={{ display: 'flex', flexDirection: 'column', gap: '2px', lineHeight: 1.2 }}>
        <strong>{baseName}</strong>
        <div style={{ fontSize: '11px', fontWeight: 400, color: '#6b7280', whiteSpace: 'normal' }}>
          {summary.split('|').map((line, index) => {
            const cleanLine = String(line || '').trim()
            return cleanLine ? <div key={`${baseName}-summary-${index}`}>{cleanLine}</div> : null
          })}
        </div>
      </div>
    )
  }

  const customFieldLines = rawFields
    .map((field, index) => {
      if (field == null) return ''
      if (typeof field === 'object') {
        const enabled = 'enabled' in field ? !!field.enabled : true
        const showInPrint = 'show_in_print' in field ? !!field.show_in_print : true
        const rawLabel = String(field.label || field.name || '').trim()
        const label = /^custom field\s*\d+$/i.test(rawLabel) ? '' : rawLabel
        const value = String(field.value ?? field.text ?? '').trim()
        if (!enabled || !showInPrint) return ''
        if (!label && !value) return ''
        return { label, value }
      }

      const value = String(field).trim()
      return value ? { label: '', value } : ''
    })
    .filter(Boolean)

  if (!customFieldLines.length) {
    return <strong>{baseName}</strong>
  }

  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: '2px', lineHeight: 1.2 }}>
      <strong>{baseName}</strong>
      <div style={{ fontSize: '11px', fontWeight: 400, color: '#6b7280', whiteSpace: 'normal' }}>
        {customFieldLines.map((field, index) => (
          <div key={`${baseName}-cf-${index}`} style={{ marginTop: '2px' }}>
            {field.label ? (
              <div style={{ display: 'flex', flexDirection: 'column', gap: '0', lineHeight: 1.1 }}>
                <span style={{ fontSize: '11px', fontWeight: 600, color: '#374151' }}>{field.label}</span>
                {field.value ? <span style={{ fontSize: '11px', color: '#6b7280' }}>{field.value}</span> : null}
              </div>
            ) : (
              <span style={{ fontSize: '11px', color: '#6b7280' }}>{field.value}</span>
            )}
          </div>
        ))}
      </div>
    </div>
  )
}

export default ItemDisplayName
