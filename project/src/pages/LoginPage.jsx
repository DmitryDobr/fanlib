import React from 'react'
import { useLocation , useNavigate } from 'react-router-dom';

import { Link } from 'react-router-dom';

import { useAuth } from '../hoc/useAuth'

import LogRegForm from '../components/LogRegForm'

const LoginPage = () => {

    const navigate = useNavigate();
    const location = useLocation();

    const {signin} = useAuth();
    const {registrate} = useAuth();

    const frompage = location.state?.from?.pathname || '/';
    // проверка на существование адреса предыдудщей страницы. В противном случае - на главную

    // событие для авторизации пользователя
    const handleSubmit = (event) => {
        event.preventDefault()
        const form = event.target;
        const user = form.username.value;
        const pass = form.password.value;

        signin(user, pass, () => navigate(frompage, {replace: true}))
        // авторизируем пользователя, переадресуем с невозможностью вернуться назад
    }

    // событие для регистрации пользователя
    const registerSubmit = (event) => {
        event.preventDefault()
        const form = event.target;

        const email = form.email.value;
        const password = form.password.value;
        const nickname = form.nickname.value;

        registrate(email,password,nickname, () => navigate(frompage, {replace: true}))
    }


    return (
        <div className = "content">
            <LogRegForm handleSubmit={handleSubmit} registerSubmit={registerSubmit}/>
        </div>
    )
}

export {LoginPage}
