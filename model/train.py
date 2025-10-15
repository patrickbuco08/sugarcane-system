# train_sugar_model.py
# -----------------------------------------------------------
# Usage:
#   python3 train_sugar_model.py /path/to/sugarcane_training_template.csv
#
# CSV columns (header required):
#   sample_code,variety,position,R,S,T,U,V,W,brix,pol
# Only R,S,T,U,V,W,brix,pol are used by the model (channels-only).
#
# Output:
#   - model_coefficients.json (saved in same folder as script)
#   - Prints intercepts/coefficients you can paste into ESP32 code.
# -----------------------------------------------------------

import sys
import json
import numpy as np
import pandas as pd
from pathlib import Path
from sklearn.linear_model import Ridge
from sklearn.model_selection import train_test_split
from sklearn.metrics import r2_score, mean_absolute_error

def main(csv_file: str):
    df = pd.read_csv(csv_file)

    # Select channels-only features
    feature_cols = ["R","S","T","U","V","W"]
    target_brix = "brix"
    target_pol  = "pol"

    # Basic validation
    for c in feature_cols + [target_brix, target_pol]:
        if c not in df.columns:
            raise ValueError(f"Missing required column: {c}")

    # Drop rows with missing values
    data = df[feature_cols + [target_brix, target_pol]].dropna()
    if len(data) < 12:
        print("[!] Warning: Very small dataset detected (<12 rows). Ridge will still run, but results may be unstable.")

    X = data[feature_cols].to_numpy(dtype=float)
    y_brix = data[target_brix].to_numpy(dtype=float)
    y_pol  = data[target_pol].to_numpy(dtype=float)

    # Train/test split for quick evaluation
    X_train, X_test, yb_train, yb_test = train_test_split(X, y_brix, test_size=0.25, random_state=42)
    _,      _,      yp_train, yp_test = train_test_split(X, y_pol,  test_size=0.25, random_state=42)

    # Ridge regression (channels-only). Alpha can be tuned.
    model_brix = Ridge(alpha=1.0, fit_intercept=True)
    model_pol  = Ridge(alpha=1.0, fit_intercept=True)

    model_brix.fit(X_train, yb_train)
    model_pol.fit(X_train, yp_train)

    # Evaluate
    yb_pred = model_brix.predict(X_test)
    yp_pred = model_pol.predict(X_test)

    print("=== Model Quality (hold-out) ===")
    print(f"Brix  -> R^2: {r2_score(yb_test, yb_pred):.3f}, MAE: {mean_absolute_error(yb_test, yb_pred):.3f}")
    print(f"Pol   -> R^2: {r2_score(yb_test, yp_pred):.3f}, MAE: {mean_absolute_error(yb_test, yp_pred):.3f}")
    print()

    # Export coefficients (ordered as R,S,T,U,V,W)
    coeffs = {
        "feature_order": feature_cols,
        "brix": {
            "intercept": float(model_brix.intercept_),
            "coefficients": [float(c) for c in model_brix.coef_]
        },
        "pol": {
            "intercept": float(model_pol.intercept_),
            "coefficients": [float(c) for c in model_pol.coef_]
        }
    }

    # Save in the same folder as the script
    out_path = Path(__file__).parent / "model_coefficients.json"
    out_path.write_text(json.dumps(coeffs, indent=2))
    print(f"[Saved] {out_path}")

    # Print C-style snippet for ESP32 usage
    def as_c_array(arr):
        return ", ".join([f"{v:.8f}f" for v in arr])

    print("\n=== Paste into ESP32 (C/C++) ===")
    print("// Order: R, S, T, U, V, W")
    print(f"const float BRIX_INTERCEPT = {coeffs['brix']['intercept']:.8f}f;")
    print(f"const float BRIX_COEFFS[6] = {{{as_c_array(coeffs['brix']['coefficients'])}}};")
    print(f"const float POL_INTERCEPT  = {coeffs['pol']['intercept']:.8f}f;")
    print(f"const float POL_COEFFS[6]  = {{{as_c_array(coeffs['pol']['coefficients'])}}};")
    print("\n// Example compute:")
    print("// float brix = BRIX_INTERCEPT + R*BRIX_COEFFS[0] + S*BRIX_COEFFS[1] + ... + W*BRIX_COEFFS[5];")
    print("// float pol  = POL_INTERCEPT  + R*POL_COEFFS[0]  + S*POL_COEFFS[1]  + ... + W*POL_COEFFS[5];")

if __name__ == '__main__':
    if len(sys.argv) < 2:
        print("Usage: python3 train_sugar_model.py /path/to/sugarcane_training_template.csv")
        sys.exit(1)
    main(sys.argv[1])
