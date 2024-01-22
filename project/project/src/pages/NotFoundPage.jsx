import { Link } from 'react-router-dom';

const Notfoundpage = () => {
    return (
        <div className='content'>
            <p>
                УПС!. Данная страница не найдена. Предлагаем вам пройти <Link className="PageHref" to="/">на главную</Link>
            </p>
        </div>

    )
}

export {Notfoundpage}