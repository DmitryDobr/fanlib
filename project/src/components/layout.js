import React, { Component } from 'react'
import { NavLink, Outlet } from 'react-router-dom';
import Header from './header';

class Layout extends Component {
  render() {
    return (
      <>
        <header>
          <Header />
        </header>
        
        <main>
          <Outlet />
        </main>

        <footer>
          <div className='content'>
            Тут подвал
          </div>
        </footer>
      </>
    )
  }
}

export default Layout;