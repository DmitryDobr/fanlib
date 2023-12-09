import React, { Component } from 'react'
import { Link } from 'react-router-dom';

import { LoginLink } from '../hoc/LoginLink';

class Header extends Component {
    
    constructor(props) {
        super(props)

        this.state = {
            head: 0,
            links: [
                {id: 0, name: "Новые работы", destination: "works/new"},
                {id: 1, name: "Случайная работа", destination: "works/rand"},
                {id: 2, name: "Завершенные", destination: "works/closed"},
                {id: 3, name: "Поиск работ", destination: "works/search"},
                {id: 4, name: "Создать работу", destination: "create/"}
            ],
        }
    }


    render() {
        return (
            <div className='content'>
                
                <div className='header-nameMenu'>
                    <Link className='SiteTitle' to="/">Библиотека фанфиков</Link>
                    <LoginLink />
                </div>

                <hr></hr>

                <div className='header-top'>
                    <div className='header-top-element' onMouseEnter={() => {
                        this.setState({
                            links: [
                                {id: 0, name: "Новые работы", destination: "works/new"},
                                {id: 1, name: "Случайная работа", destination: "works/rand"},
                                {id: 2, name: "Завершенные", destination: "works/closed"},
                                {id: 3, name: "Поиск работ", destination: "works/search"},
                                {id: 4, name: "Создать работу", destination: "create/"}
                            ]
                        })
                    }}>Работы</div>
                    <div className='header-top-element' onMouseEnter={() => {
                        this.setState({
                            links: [
                                {id: 0, name: "Популярные авторы", destination: "/authors/popular"},
                                {id: 1, name: "Новые авторы", destination: "/authors/new"},
                                {id: 2, name: "Случайные авторы", destination: "/authors/random"}
                            ]
                        })
                    }}>Авторы</div>
                    <div className='header-top-element' onMouseEnter={() => {
                        this.setState({
                            links: [
                                {id: 0, name: "Популярные фандомы", destination: "/fandoms/popular"},
                                {id: 1, name: "Преложить фандом", destination: "/fandoms/create"},
                                {id: 2, name: "Поиск фандомов", destination: "/fandoms/search"}
                            ]
                        })
                    }}>Фандомы</div>
                    <div className='header-top-element' onMouseEnter={() => {
                        this.setState({
                            links: [
                                {id: 0, name: "Правила сайта", destination: "/"},
                                {id: 1, name: "Правила размещения работ", destination: "/"}
                            ]
                        })
                    }}>FAQ</div>
                </div>

                <div className='header-bottom'>
                    {this.state.links.map((el) => (
                        <Link key={el.id} className='header-bottom-element' to={el.destination}>{el.name}</Link>
                    ))}
                </div>
                
            </div>
        )
    }
}

export default Header;