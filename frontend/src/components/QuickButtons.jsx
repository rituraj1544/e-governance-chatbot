const options = [
  ['aadhaar', 'Aadhaar'], ['pan_card', 'PAN Card'], ['ration_card', 'Ration Card'],
  ['pm_kisan', 'PM-Kisan'], ['scholarship', 'Scholarships'], ['general_scheme', 'Schemes'],
]

export default function QuickButtons({ onChoose, disabled }) {
  return <div className="quick-actions"><span>Try asking about</span>{options.map(([intent, label]) => <button type="button" key={intent} disabled={disabled} onClick={() => onChoose(label)}>{label}</button>)}</div>
}
