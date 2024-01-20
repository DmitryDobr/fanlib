import React, { Component } from 'react'
import { Link } from 'react-router-dom'

export default class LogRegForm extends Component {

    constructor(props) {
        super(props)

        this.state = {
            reg: false,
        }
    }

    render() {
        if (!this.state.reg)
        {
            return (
                <form onSubmit={this.props.handleSubmit} className='LoginForm'>
                    <span className='LoginForm-Title'>Авторизация</span>
    
                    <input className='LoginForm-text' name='username' placeholder='адрес эл.почты' required/>
                
                    <input className='LoginForm-text' type='password' name='password' placeholder='пароль' required/>
                    
                    <button className='LoginForm-button' type='submit'>Вход</button>
    
                    <span className='LoginForm-BottomText'>Нет аккаунта? <span onClick={() => {this.setState({reg: !this.state.reg})}} className='LoginForm-BottomLink' to='/registration'>Зарегистрироваться</span></span>
                </form>
            )
        }
        else
        {
            return (
                <form onSubmit={this.props.registerSubmit} className='LoginForm'>
                    
                    <span className='LoginForm-Title'>Регистрация</span>
    
                    <input className='LoginForm-text' name='email' placeholder='адрес эл.почты' required/>
                
                    <input className='LoginForm-text' type='password' name='password' placeholder='пароль' required/>
                    
                    <input className='LoginForm-text' type='nickname' name='nickname' placeholder='имя пользователя' required/>
                    
                    <button className='LoginForm-button' type='submit'>Регистрация</button>
    
                    <span className='LoginForm-BottomText'>Регистрируясь на сайте вы соглашаетесь с нашей политикой и т.д. и т.п.</span>
                </form>
            )
        }
        
    }
}
