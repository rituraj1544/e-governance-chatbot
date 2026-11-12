export default function FormInput({ label, id, error, hint, ...props }) {
  return <label className="form-field" htmlFor={id}><span className="field-label">{label}</span><input id={id} aria-invalid={Boolean(error)} aria-describedby={error ? `${id}-error` : undefined} {...props} />{hint && <small className="field-hint">{hint}</small>}{error && <small className="field-error" id={`${id}-error`}>{error}</small>}</label>
}
