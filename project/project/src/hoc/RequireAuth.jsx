// Компонент высшего порядка
// использует локацию и редиректы
// примерный пример с моментом с авторизацией и регистрацией

import React from 'react'
import { useLocation , Navigate} from 'react-router-dom'

import { useAuth } from './useAuth'

const RequireAuth = ({children}) => {
    // в роли чилдрена = любая из страниц определенных в Routes

    const location = useLocation();

    const {user} = useAuth();

    // const auth = false; // потом будет компонент представляющий информацию об авторизации

    if (!user) {
        return <Navigate to='/login' state={{from: location}} />

        // переадресация на страницу логирования с передачей в state информации о странице, к которой пытались получить доступ
    }
    
    return children
}


export {RequireAuth}