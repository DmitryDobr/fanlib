import React from 'react'
import { Link } from 'react-router-dom';

const Chapter = (props) => {

    // parameters
    // 0 - номер
    // 1 - имя главы
    // 2 - id главы
    // 3 - id работы

    // console.log(props.parameters)

    let number = props.parameters[0]
    let nameChapter = props.parameters[1]
    let chapterId = props.parameters[2]
    let workId = props.parameters[3]

    return (
        <>
            <div className='Chapter'>
                <div className='Chapter-Info'>
                    <span>Глава {number}</span>
                    <span>—</span>
                    <span>{nameChapter}</span>
                </div>
                <div>
                    <Link to={`/read/${workId}/part/${chapterId}`}>Читать</Link>
                </div>
            </div>
        </>
        // <div>as</div>
    )
}

export {Chapter}
