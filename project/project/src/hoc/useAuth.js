import { useContext } from "react";
import { AuthContext } from "./AuthProvider";

export function useAuth () {
    return useContext(AuthContext);
}

// вызов хука позволяет дотянуться до user и его методов в AuthProvider