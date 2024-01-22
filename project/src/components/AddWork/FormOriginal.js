import React from 'react'
import { useState, useEffect } from "react"

const FormOriginal = (props) => {

    var [increment, setInc] = useState(0);

    // удаление главы из работы
    async function addCharacter() {
        const elem = document.getElementById('character_input');
        const name = elem.value;

        props.InsertCharacter(name, increment, 0)

        setInc(increment + 1);
    }

    return (
        <div className='filter'>
            <input className='EditForm-text' id='character_input' placeholder='Введите имя персонажа'></input>

            <input type='button' className='LoginForm-button' id='selector-button' 
                    onClick={() => {addCharacter()}} 
                    value={'Добавить'}
            ></input>
        </div>
    )
}

export {FormOriginal}