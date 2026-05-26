import { formatCurrency } from './invoiceData'

const AdjustmentSummaryRows = ({
  rows = [],
  rowClassName = '',
  labelClassName = '',
  colonClassName = '',
  valueClassName = '',
  containerClassName = '',
  showColon = false,
}) => {
  if (!Array.isArray(rows) || !rows.length) {
    return null
  }

  return (
    <div className={containerClassName}>
      {rows.map((row, index) => (
        <div className={rowClassName} key={`${row.label}-${index}`}>
          <span className={labelClassName}>{row.label}</span>
          {showColon ? <span className={colonClassName}>:</span> : null}
          <span className={valueClassName}>{formatCurrency(row.amount)}</span>
        </div>
      ))}
    </div>
  )
}

export default AdjustmentSummaryRows
